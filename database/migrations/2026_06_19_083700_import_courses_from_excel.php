<?php

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::disableForeignKeyConstraints();
        Course::truncate();
        CourseCategory::truncate();
        Schema::enableForeignKeyConstraints();

        $categories = [];
        foreach ($this->categoriesData() as $categoryData) {
            $categories[$categoryData['slug']] = CourseCategory::create($categoryData);
        }

        foreach ($this->coursesData() as $courseData) {
            $category = $categories[$courseData['category_slug']]
                ?? throw new RuntimeException("Missing course category [{$courseData['category_slug']}].");

            $title = $this->normalizeLine($courseData['title']);
            $description = $this->normalizeMultiline($courseData['description'] ?? '');
            $detail = $this->normalizeMultiline($courseData['detail'] ?? '');
            $seoSource = $this->plainText($description !== '' ? $description : $detail);

            Course::create([
                'course_category_id' => $category->id,
                'title' => $title,
                'slug' => $this->uniqueSlug($title),
                'description' => $description,
                'content' => $this->buildContent($description),
                'image' => null,
                'meta_title' => Str::limit($title.' | GREECO', 70, ''),
                'meta_description' => Str::limit($seoSource !== '' ? $seoSource : $this->defaultMetaDescription($title), 160, ''),
                'meta_keywords' => Str::limit($this->buildKeywords($title, $category->name), 250, ''),
                'canonical_url' => null,
                'og_image' => null,
                'tag' => $category->name,
                'badge_color' => 'bg-success',
                'price' => 'Liên hệ',
                'original_price' => null,
                'discount' => null,
                'instructor_name' => null,
                'instructor_role' => null,
                'instructor_image' => null,
                'instructor_description' => null,
                'curriculum' => $this->buildCurriculum($detail !== '' ? $detail : $description),
                'is_indexable' => true,
                'is_active' => true,
                'sort_order' => (int) $courseData['sort_order'],
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Course::truncate();
        CourseCategory::truncate();
        Schema::enableForeignKeyConstraints();
    }

    private function uniqueSlug(string $title): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $suffix = 2;

        while (Course::query()->where('slug', $slug)->exists()) {
            $slug = $baseSlug.'-'.$suffix;
            $suffix++;
        }

        return $slug;
    }

    private function buildContent(string $description): string
    {
        if ($description === '') {
            return '';
        }

        $paragraphs = array_map(
            fn (string $line): string => '<p>'.e($line).'</p>',
            $this->splitLines($description)
        );

        return '<h3><strong>'.$this->defaultChapterTitle().'</strong></h3>'.PHP_EOL.implode(PHP_EOL, $paragraphs).PHP_EOL;
    }

    private function buildCurriculum(string $source): array
    {
        $lines = $this->splitLines($source);
        if ($lines === []) {
            return [];
        }

        $chapterStyle = $this->detectChapterStyle($lines);
        $chapters = [];
        $currentChapter = null;

        for ($index = 0, $lineCount = count($lines); $index < $lineCount; $index++) {
            $line = $lines[$index];

            if ($this->isSkippableLabel($line)) {
                continue;
            }

            $inlineDuration = $this->extractInlineDuration($line);
            if ($inlineDuration !== null) {
                $currentChapter = $this->ensureChapter($currentChapter);
                $currentChapter['lessons'][] = [
                    'title' => $this->durationLabel(),
                    'duration' => $inlineDuration,
                ];

                continue;
            }

            if ($this->isDurationLabel($line) && isset($lines[$index + 1]) && $this->isDurationValue($lines[$index + 1])) {
                $currentChapter = $this->ensureChapter($currentChapter);
                $currentChapter['lessons'][] = [
                    'title' => $this->normalizeLine($line),
                    'duration' => $this->normalizeLine($lines[$index + 1]),
                ];
                $index++;

                continue;
            }

            if ($this->matchesChapterStyle($line, $chapterStyle)) {
                if ($currentChapter !== null) {
                    $chapters[] = $currentChapter;
                }

                [$title, $skipNext] = $this->composeMarkerTitle($line, $lines, $index);
                $currentChapter = [
                    'title' => $title,
                    'lessons' => [],
                ];
                $index += $skipNext;

                continue;
            }

            $currentChapter = $this->ensureChapter($currentChapter);

            if ($this->isDurationValue($line)) {
                $currentChapter['lessons'][] = [
                    'title' => $this->durationTitle(),
                    'duration' => $this->normalizeLine($line),
                ];

                continue;
            }

            [$lessonTitle, $skipNext] = $this->composeLessonTitle($line, $lines, $index);
            $currentChapter['lessons'][] = ['title' => $lessonTitle];
            $index += $skipNext;
        }

        if ($currentChapter !== null) {
            $chapters[] = $currentChapter;
        }

        return array_values(array_filter($chapters, fn (array $chapter): bool => $chapter['title'] !== ''));
    }

    private function ensureChapter(?array $chapter): array
    {
        return $chapter ?? [
            'title' => $this->defaultChapterTitle(),
            'lessons' => [],
        ];
    }

    /**
     * @param  array<int, string>  $lines
     */
    private function detectChapterStyle(array $lines): string
    {
        foreach (['giai_doan', 'ngay', 'phan', 'chuyen_de'] as $style) {
            foreach ($lines as $line) {
                if ($this->markerType($line) === $style) {
                    return $style;
                }
            }
        }

        return 'none';
    }

    private function matchesChapterStyle(string $line, string $chapterStyle): bool
    {
        return $chapterStyle !== 'none' && $this->markerType($line) === $chapterStyle;
    }

    private function markerType(string $line): ?string
    {
        $line = $this->asciiLine($line);

        return match (true) {
            (bool) preg_match('/^GIAI\s*DOAN\s+\d+/i', $line) => 'giai_doan',
            (bool) preg_match('/^NGAY\s+\d+/i', $line) => 'ngay',
            (bool) preg_match('/^PHAN\s+\d+/i', $line) => 'phan',
            (bool) preg_match('/^Chuyen\s*de\s+\d+/i', $line) => 'chuyen_de',
            default => null,
        };
    }

    /**
     * @param  array<int, string>  $lines
     * @return array{0: string, 1: int}
     */
    private function composeMarkerTitle(string $line, array $lines, int $index): array
    {
        $line = $this->normalizeLine($line);

        if (! $this->isBareMarker($line) || ! isset($lines[$index + 1])) {
            return [$line, 0];
        }

        $nextLine = $this->normalizeLine($lines[$index + 1]);
        if ($this->shouldNotMergeWithPreviousMarker($nextLine)) {
            return [$line, 0];
        }

        return [$line.' – '.$nextLine, 1];
    }

    /**
     * @param  array<int, string>  $lines
     * @return array{0: string, 1: int}
     */
    private function composeLessonTitle(string $line, array $lines, int $index): array
    {
        $line = $this->normalizeLine($line);

        if (! $this->isBareMarker($line) || ! isset($lines[$index + 1])) {
            return [$line, 0];
        }

        $nextLine = $this->normalizeLine($lines[$index + 1]);
        if ($this->shouldNotMergeWithPreviousMarker($nextLine)) {
            return [$line, 0];
        }

        return [$line.' – '.$nextLine, 1];
    }

    private function shouldNotMergeWithPreviousMarker(string $line): bool
    {
        return $line === ''
            || $this->markerType($line) !== null
            || $this->isSkippableLabel($line)
            || $this->isDurationValue($line)
            || $this->isDurationLabel($line);
    }

    private function isBareMarker(string $line): bool
    {
        return (bool) preg_match('/^(GIAI\s*DOAN|NGAY|PHAN|Chuyen\s*de)\s+\d+$/i', $this->asciiLine($line));
    }

    private function isSkippableLabel(string $line): bool
    {
        return (bool) preg_match('/^Noi dung (dao tao|thuc hien|thuc hanh)\s*:?$/i', $this->asciiLine($line));
    }

    private function isDurationLabel(string $line): bool
    {
        return (bool) preg_match('/^Thoi gian du kien\s*:?$/i', $this->asciiLine($line));
    }

    private function extractInlineDuration(string $line): ?string
    {
        if (! preg_match('/^Thoi gian du kien\s*[:]\s*(.+)$/i', $this->asciiLine($line))) {
            return null;
        }

        $parts = preg_split('/[:]/u', $line, 2);

        return isset($parts[1]) ? $this->normalizeLine($parts[1]) : null;
    }

    private function isDurationValue(string $line): bool
    {
        return (bool) preg_match('/^\d+\s*(?:[-\x{2013}]\s*\d+\s*)?(?:ngay|buoi|gio|thang)(?:\s+.+)?$/iu', $this->asciiLine($line));
    }

    /**
     * @return array<int, string>
     */
    private function splitLines(string $value): array
    {
        $normalized = $this->normalizeMultiline($value);
        if ($normalized === '') {
            return [];
        }

        return array_values(array_filter(
            array_map(fn (string $line): string => $this->normalizeLine($line), preg_split('/\R/u', $normalized) ?: []),
            fn (string $line): bool => $line !== ''
        ));
    }

    private function normalizeMultiline(string $value): string
    {
        $value = str_replace(["\r\n", "\r", "\u{00A0}"], ["\n", "\n", ' '], $value);
        $lines = array_map(fn (string $line): string => $this->normalizeLine($line), explode("\n", $value));

        return trim(implode("\n", array_filter($lines, fn (string $line): bool => $line !== '')));
    }

    private function normalizeLine(string $value): string
    {
        $value = html_entity_decode($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $value = str_replace(["\u{00A0}", '•', '·'], [' ', '', ''], $value);
        $value = preg_replace('/^[\-+*]\s*/u', '', $value) ?? $value;
        $value = preg_replace('/\s+/u', ' ', $value) ?? $value;

        return trim($value);
    }

    private function asciiLine(string $value): string
    {
        return Str::ascii($this->normalizeLine($value));
    }

    private function plainText(string $value): string
    {
        return trim(preg_replace('/\s+/u', ' ', strip_tags($this->normalizeMultiline($value))) ?? '');
    }

    private function buildKeywords(string $title, string $categoryName): string
    {
        return implode(', ', array_unique(array_filter([
            $title,
            $categoryName,
            'dao tao GREECO',
            'dao tao phat trien ben vung',
            'tu van doanh nghiep',
        ])));
    }

    private function defaultMetaDescription(string $title): string
    {
        return html_entity_decode('Ch&#432;&#417;ng tr&#236;nh &#273;&#224;o t&#7841;o ', ENT_QUOTES | ENT_HTML5, 'UTF-8')
            .$title
            .html_entity_decode(' t&#7841;i Vi&#7879;n GREECO.', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function defaultChapterTitle(): string
    {
        return html_entity_decode('N&#7897;i dung &#273;&#224;o t&#7841;o', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function durationLabel(): string
    {
        return html_entity_decode('Th&#7901;i gian d&#7921; ki&#7871;n', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function durationTitle(): string
    {
        return html_entity_decode('Th&#7901;i l&#432;&#7907;ng', ENT_QUOTES | ENT_HTML5, 'UTF-8');
    }

    private function categoriesData(): array
    {
        return json_decode(<<<'JSON'
[
  {
    "name": "Hệ thống quản lý ISO và cải tiến doanh nghiệp",
    "slug": "iso",
    "icon": "fa-certificate",
    "color": "#008f79",
    "bg": "#e6f4f1",
    "sort_order": 1
  },
  {
    "name": "An toàn – Sức khỏe – Môi trường (HSE)",
    "slug": "hse",
    "icon": "fa-shield-heart",
    "color": "#1f487e",
    "bg": "#e8eef5",
    "sort_order": 2
  },
  {
    "name": "Kiểm kê khí nhà kính và quản lý carbon",
    "slug": "carbon",
    "icon": "fa-cloud",
    "color": "#03a9f4",
    "bg": "#e1f5fe",
    "sort_order": 3
  },
  {
    "name": "ESG và phát triển bền vững",
    "slug": "esg",
    "icon": "fa-leaf",
    "color": "#4caf50",
    "bg": "#edf7ed",
    "sort_order": 4
  },
  {
    "name": "Chuỗi cung ứng bền vững và kinh tế tuần hoàn",
    "slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
    "icon": "fa-globe",
    "color": "#009688",
    "bg": "#e0f2f1",
    "sort_order": 5
  },
  {
    "name": "Công trình xanh và sản phẩm bền vững",
    "slug": "cong-trinh-xanh-va-san-pham-ben-vung",
    "icon": "fa-building-shield",
    "color": "#607d8b",
    "bg": "#eceff1",
    "sort_order": 6
  }
]
JSON, true, 512, JSON_THROW_ON_ERROR);
    }

    private function coursesData(): array
    {
        return json_decode(<<<'JSON'
[
  {
    "category_slug": "iso",
    "title": "Xây dựng Hệ thống Quản lý Chất lượng ISO 9001:2015",
    "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU\nGIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG\nGIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
    "detail": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nNội dung thực hiện\n• Thành lập Ban ISO\n• Xây dựng kế hoạch triển khai\n• Phân công vai trò và trách nhiệm\n• Đào tạo nhận thức ISO 9001:2015\n• Xây dựng chính sách chất lượng\n• Xây dựng mục tiêu chất lượng\nThời gian dự kiến\n02 ngày\nGIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU\nNội dung thực hiện\n• Xây dựng quy trình quản lý\n• Xây dựng SOP (Standard Operating Procedure – Quy trình thao tác chuẩn)\n• Xây dựng hướng dẫn công việc\n• Xây dựng biểu mẫu quản lý\n• Thiết lập hệ thống kiểm soát tài liệu và hồ sơ\n• Thiết lập KPI chất lượng\nThời gian dự kiến\n04 – 05 ngày\nGIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG\nNội dung thực hiện\n• Hướng dẫn áp dụng quy trình\n• Hướng dẫn sử dụng biểu mẫu\n• Coaching áp dụng thực tế tại hiện trường\n• Kiểm soát hồ sơ và tài liệu\n• Theo dõi việc áp dụng hệ thống\n• Hướng dẫn kiểm soát sản phẩm không phù hợp\nThời gian dự kiến\n03 – 04 ngày\nGIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ\nNội dung thực hiện\n• Đào tạo Internal Auditor ISO 9001:2015\n• Kỹ năng đánh giá nội bộ\n• Kỹ năng viết báo cáo đánh giá\n• Root Cause Analysis\n• CAPA Management\nThời gian dự kiến\n02 ngày\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG\nNội dung thực hiện\n• Thực hiện Internal Audit\n• Theo dõi hành động khắc phục\n• Họp xem xét lãnh đạo\n• Hoàn thiện hồ sơ hệ thống\n• Chuẩn bị đánh giá chứng nhận ISO 9001:2015\nThời gian dự kiến\n02 ngày",
    "sort_order": 1
  },
  {
    "category_slug": "iso",
    "title": "Xây dựng Hệ thống Quản lý Môi trường ISO 14001:2015",
    "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU\nGIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG\nGIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
    "detail": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nNội dung thực hiện\n• Thành lập Ban ISO\n• Xây dựng kế hoạch triển khai\n• Phân công vai trò và trách nhiệm\n• Đào tạo nhận thức ISO 14001:2015\n• Xây dựng chính sách môi trường\n• Xây dựng mục tiêu môi trường\nThời gian dự kiến\n02 ngày\nGIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU\nNội dung thực hiện\n• Xây dựng quy trình quản lý môi trường\n• Xây dựng SOP (Standard Operating Procedure – Quy trình thao tác chuẩn)\n• Xây dựng hướng dẫn công việc\n• Xây dựng biểu mẫu quản lý môi trường\n• Thiết lập hệ thống kiểm soát tài liệu và hồ sơ\n• Thiết lập KPI môi trường\n• Xây dựng ma trận khía cạnh và tác động môi trường\n• Xây dựng danh mục yêu cầu pháp luật môi trường\nThời gian dự kiến\n04 – 05 ngày\nGIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG\nNội dung thực hiện\n• Hướng dẫn áp dụng quy trình môi trường\n• Hướng dẫn nhận diện khía cạnh môi trường\n• Hướng dẫn kiểm soát chất thải\n• Hướng dẫn quản lý hóa chất\n• Hướng dẫn kiểm soát sự cố môi trường\n• Coaching áp dụng thực tế tại hiện trường\n• Kiểm soát hồ sơ và tài liệu\n• Theo dõi việc áp dụng hệ thống\nThời gian dự kiến\n03 – 04 ngày\nGIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ\nNội dung thực hiện\n• Đào tạo Internal Auditor ISO 14001:2015\n• Kỹ năng đánh giá nội bộ\n• Kỹ năng viết báo cáo đánh giá\n• Root Cause Analysis\n• CAPA Management\n• Đánh giá tuân thủ pháp luật môi trường\nThời gian dự kiến\n02 ngày\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG\nNội dung thực hiện\n• Thực hiện Internal Audit\n• Theo dõi hành động khắc phục\n• Họp xem xét lãnh đạo\n• Hoàn thiện hồ sơ hệ thống\n• Chuẩn bị đánh giá chứng nhận ISO 14001:2015\nThời gian dự kiến\n02 ngày",
    "sort_order": 2
  },
  {
    "category_slug": "iso",
    "title": "Xây dựng Hệ thống Quản lý An toàn và Sức khỏe Nghề nghiệp ISO 45001:2018",
    "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU\nGIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG\nGIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
    "detail": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nNội dung thực hiện\n• Thành lập Ban ISO\n• Xây dựng kế hoạch triển khai\n• Phân công vai trò và trách nhiệm\n• Đào tạo nhận thức ISO 45001:2018\n• Xây dựng chính sách an toàn và sức khỏe nghề nghiệp\n• Xây dựng mục tiêu an toàn\nThời gian dự kiến\n02 ngày\nGIAI ĐOẠN 2 – XÂY DỰNG HỆ THỐNG TÀI LIỆU\nNội dung thực hiện\n• Xây dựng quy trình quản lý an toàn\n• Xây dựng SOP (Standard Operating Procedure – Quy trình thao tác chuẩn)\n• Xây dựng hướng dẫn công việc an toàn\n• Xây dựng biểu mẫu quản lý an toàn\n• Thiết lập hệ thống kiểm soát tài liệu và hồ sơ\n• Thiết lập KPI an toàn\n• Xây dựng ma trận nhận diện mối nguy và đánh giá rủi ro\n• Xây dựng danh mục yêu cầu pháp luật an toàn lao động\n• Xây dựng quy trình ứng phó tình huống khẩn cấp\nThời gian dự kiến\n04 – 05 ngày\nGIAI ĐOẠN 3 – TRIỂN KHAI ÁP DỤNG HỆ THỐNG\nNội dung thực hiện\n• Hướng dẫn áp dụng quy trình an toàn\n• Hướng dẫn nhận diện mối nguy và đánh giá rủi ro\n• Hướng dẫn kiểm soát nhà thầu\n• Hướng dẫn kiểm soát công việc nguy hiểm\n• Hướng dẫn điều tra tai nạn và sự cố\n• Hướng dẫn kiểm soát hóa chất và thiết bị\n• Coaching áp dụng thực tế tại hiện trường\n• Kiểm soát hồ sơ và tài liệu\n• Theo dõi việc áp dụng hệ thống\nThời gian dự kiến\n03 – 04 ngày\nGIAI ĐOẠN 4 – ĐÀO TẠO ĐÁNH GIÁ NỘI BỘ\nNội dung thực hiện\n• Đào tạo Internal Auditor ISO 45001:2018\n• Kỹ năng đánh giá nội bộ\n• Kỹ năng viết báo cáo đánh giá\n• Root Cause Analysis\n• CAPA Management\n• Đánh giá tuân thủ pháp luật an toàn lao động\nThời gian dự kiến\n02 ngày\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG\nNội dung thực hiện\n• Thực hiện Internal Audit\n• Theo dõi hành động khắc phục\n• Họp xem xét lãnh đạo\n• Hoàn thiện hồ sơ hệ thống\n• Chuẩn bị đánh giá chứng nhận ISO 45001:2018\nThời gian dự kiến\n02 ngày",
    "sort_order": 3
  },
  {
    "category_slug": "iso",
    "title": "Xây dựng Hệ thống Quản lý Năng lượng ISO 50001:2018",
    "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – ĐÁNH GIÁ NĂNG LƯỢNG VÀ XÁC ĐỊNH SEU\nGIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG QUẢN LÝ NĂNG LƯỢNG\nGIAI ĐOẠN 4 – TRIỂN KHAI ÁP DỤNG VÀ ĐÀO TẠO\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG",
    "detail": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nNội dung thực hiện\n• Thành lập Ban Energy Team\n• Xây dựng kế hoạch triển khai\n• Phân công vai trò và trách nhiệm\n• Đào tạo nhận thức ISO 50001:2018\n• Tổng quan về quản lý năng lượng\n• Tổng quan về Energy Performance\n• Tổng quan về GHG và Carbon Management\n• Xây dựng chính sách năng lượng\n• Xây dựng mục tiêu năng lượng\nThời gian dự kiến\n02 ngày\nGIAI ĐOẠN 2 – ĐÁNH GIÁ NĂNG LƯỢNG VÀ XÁC ĐỊNH SEU\nNội dung thực hiện\n• Thu thập dữ liệu năng lượng\n• Phân tích sử dụng năng lượng\n• Xác định Significant Energy Use (SEU)\n• Đánh giá hiệu suất thiết bị\n• Đánh giá hệ thống điện\n• Đánh giá hệ thống khí nén\n• Đánh giá hệ thống hơi\n• Đánh giá động cơ và thiết bị tiêu thụ năng lượng\n• Xây dựng Energy Review\nThời gian dự kiến\n03 – 04 ngày\nGIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG QUẢN LÝ NĂNG LƯỢNG\nNội dung thực hiện\n• Xây dựng quy trình quản lý năng lượng\n• Xây dựng SOP vận hành năng lượng\n• Thiết lập Energy Baseline\n• Thiết lập Energy Performance Indicator (EnPI)\n• Thiết lập hệ thống giám sát năng lượng\n• Xây dựng KPI năng lượng\n• Xây dựng kế hoạch tiết kiệm năng lượng\n• Thiết lập hệ thống quản lý dữ liệu năng lượng\nThời gian dự kiến\n03 – 04 ngày\nGIAI ĐOẠN 4 – TRIỂN KHAI ÁP DỤNG VÀ ĐÀO TẠO\nNội dung thực hiện\n• Hướng dẫn áp dụng hệ thống ISO 50001\n• Coaching hiện trường\n• Đào tạo kiểm soát vận hành năng lượng\n• Đào tạo quản lý dữ liệu năng lượng\n• Đào tạo Internal Auditor ISO 50001\n• Theo dõi hiệu quả áp dụng thực tế\nThời gian dự kiến\n02 – 03 ngày\nGIAI ĐOẠN 5 – ĐÁNH GIÁ NỘI BỘ VÀ HOÀN THIỆN HỆ THỐNG\nNội dung thực hiện\n• Thực hiện Internal Audit\n• Theo dõi hành động khắc phục\n• Họp xem xét lãnh đạo\n• Rà soát hệ thống năng lượng\n• Hoàn thiện hồ sơ chứng nhận\n• Chuẩn bị đánh giá chứng nhận ISO 50001:2018\nThời gian dự kiến\n02 ngày",
    "sort_order": 4
  },
  {
    "category_slug": "iso",
    "title": "Xây dựng Hệ thống Quản lý Hiệu quả Sử dụng Nước ISO 46001:2019",
    "description": "",
    "detail": "",
    "sort_order": 5
  },
  {
    "category_slug": "hse",
    "title": "Đánh giá Rủi ro An toàn – Sức khỏe – Môi trường",
    "description": "NGÀY 1 – NHẬN DIỆN MỐI NGUY VÀ ĐÁNH GIÁ RỦI RO\nNGÀY 2 – KIỂM SOÁT RỦI RO VÀ TRIỂN KHAI THỰC TẾ",
    "detail": "NGÀY 1 – NHẬN DIỆN MỐI NGUY VÀ ĐÁNH GIÁ RỦI RO\nPHẦN 1 – TỔNG QUAN VỀ ĐÁNH GIÁ RỦI RO\nNội dung đào tạo\n• Khái niệm Hazard và Risk\n• Tư duy phòng ngừa trong công tác an toàn\n• Risk-Based Thinking\n• Tai nạn và nguyên nhân tai nạn\n• Yêu cầu pháp luật liên quan\n• Yêu cầu ISO 45001 và ISO 14001\n• Vai trò của đánh giá rủi ro trong doanh nghiệp\nPHẦN 2 – NHẬN DIỆN MỐI NGUY\nNội dung đào tạo\n• Phương pháp nhận diện mối nguy\n• Nhận diện mối nguy theo công việc\n• Nhận diện mối nguy theo thiết bị\n• Nhận diện mối nguy theo khu vực\n• Nhận diện hành vi không an toàn\n• Nhận diện điều kiện không an toàn\n• Nhận diện tác động môi trường\nCác nhóm mối nguy chính\n• Cơ học\n• Điện\n• Hóa chất\n• Cháy nổ\n• Tiếng ồn\n• Nhiệt\n• Ergonomic\n• Làm việc trên cao\n• Không gian hạn chế\n• Thiết bị nâng\n• Xe nâng và giao thông nội bộ\nPHẦN 3 – PHƯƠNG PHÁP ĐÁNH GIÁ RỦI RO\nNội dung đào tạo\n• Xác định khả năng xảy ra\n• Xác định mức độ hậu quả\n• Xác định mức độ rủi ro\n• Ma trận đánh giá rủi ro\n• Phân loại mức độ rủi ro\n• Xác định mức độ chấp nhận rủi ro\nCác phương pháp áp dụng\n• HIRA (Hazard Identification and Risk Assessment)\n• JSA (Job Safety Analysis)\n• What-if Analysis\n• Checklist Method\n• Bowtie Concept\nPHẦN 4 – THỰC HÀNH NHẬN DIỆN MỐI NGUY\nNội dung thực hành\n• Thực hành nhận diện mối nguy tại hiện trường\n• Thực hành đánh giá rủi ro theo công việc\n• Phân tích tình huống tai nạn thực tế\n• Thảo luận nhóm và trình bày kết quả\nNGÀY 2 – KIỂM SOÁT RỦI RO VÀ TRIỂN KHAI THỰC TẾ\nPHẦN 5 – BIỆN PHÁP KIỂM SOÁT RỦI RO\nNội dung đào tạo\n• Hierarchy of Controls\n• Elimination\n• Substitution\n• Engineering Control\n• Administrative Control\n• PPE (Personal Protective Equipment – Phương tiện bảo vệ cá nhân)\nThiết lập biện pháp kiểm soát\n• Kiểm soát kỹ thuật\n• Kiểm soát hành chính\n• SOP an toàn\n• Permit To Work\n• Lockout Tagout\n• Biển báo và cảnh báo\n• Kiểm tra và giám sát\nPHẦN 6 – ĐÁNH GIÁ RỦI RO TRONG CÁC CÔNG VIỆC NGUY HIỂM\nNội dung đào tạo\n• Làm việc trên cao\n• Hot Work\n• Không gian hạn chế\n• Điện\n• Thiết bị nâng\n• Hóa chất nguy hiểm\n• Xe nâng\n• Bảo trì máy móc\n• Nhà thầu thi công\nPHẦN 7 – ĐIỀU TRA TAI NẠN VÀ ROOT CAUSE ANALYSIS\nNội dung đào tạo\n• Quy trình điều tra tai nạn\n• Thu thập bằng chứng\n• Phân tích nguyên nhân trực tiếp\n• Phân tích nguyên nhân gốc rễ\n• 5 Why\n• Fishbone Diagram\n• Thiết lập CAPA\nPHẦN 8 – TRIỂN KHAI HỆ THỐNG ĐÁNH GIÁ RỦI RO TẠI DOANH NGHIỆP\nNội dung đào tạo\n• Thiết lập Risk Assessment Procedure\n• Thiết lập Risk Register\n• Cập nhật đánh giá rủi ro\n• Đánh giá rủi ro theo thay đổi\n• Theo dõi hành động kiểm soát\n• Tích hợp với ISO 45001 và ISO 14001\nPHẦN 9 – THỰC HÀNH VÀ ĐÁNH GIÁ CUỐI KHÓA\nNội dung thực hiện\n• Thực hành đánh giá rủi ro theo nhóm\n• Trình bày kết quả\n• Coaching và góp ý\n• Kiểm tra cuối khóa\n• Tổng kết đào tạo",
    "sort_order": 6
  },
  {
    "category_slug": "hse",
    "title": "Quản lý Hóa chất trong Doanh nghiệp",
    "description": "NGÀY 1 – TỔNG QUAN VỀ QUẢN LÝ HÓA CHẤT VÀ NHẬN DIỆN RỦI RO\nNGÀY 2 – KIỂM SOÁT HÓA CHẤT VÀ ỨNG PHÓ SỰ CỐ",
    "detail": "NGÀY 1 – TỔNG QUAN VỀ QUẢN LÝ HÓA CHẤT VÀ NHẬN DIỆN RỦI RO\nPHẦN 1 – TỔNG QUAN VỀ QUẢN LÝ HÓA CHẤT\nNội dung đào tạo\n• Tổng quan về hóa chất trong doanh nghiệp\n• Nguy cơ và tác động của hóa chất\n• Các sự cố hóa chất điển hình\n• Yêu cầu pháp luật liên quan\n• Yêu cầu ISO 14001 và ISO 45001 liên quan đến hóa chất\n• Vai trò và trách nhiệm trong quản lý hóa chất\nPHẦN 2 – NHẬN DIỆN MỐI NGUY HÓA CHẤT\nNội dung đào tạo\n• Phân loại hóa chất nguy hiểm\n• Nhận diện mối nguy hóa chất\n• Tính chất vật lý và hóa học nguy hiểm\n• Hóa chất cháy nổ\n• Hóa chất ăn mòn\n• Hóa chất độc hại\n• Hóa chất phản ứng mạnh\n• Tác động sức khỏe nghề nghiệp\n• Tác động môi trường\nPHẦN 3 – SDS VÀ GHS\nNội dung đào tạo\n• Cấu trúc SDS\n• Cách đọc SDS\n• Nhãn GHS\n• Pictogram cảnh báo\n• Signal Word\n• Hazard Statement\n• Precautionary Statement\n• Yêu cầu ghi nhãn hóa chất\nNội dung thực hành\n• Thực hành đọc SDS\n• Nhận diện nhãn GHS\n• Phân tích thông tin hóa chất thực tế\nPHẦN 4 – ĐÁNH GIÁ RỦI RO HÓA CHẤT\nNội dung đào tạo\n• Nhận diện rủi ro hóa chất\n• Đánh giá mức độ rủi ro\n• Exposure Assessment\n• Risk Matrix\n• Biện pháp kiểm soát hóa chất\n• Hierarchy of Controls\nNội dung thực hành\n• Thực hành đánh giá rủi ro hóa chất\n• Thảo luận tình huống thực tế\n• Coaching hiện trường\nNGÀY 2 – KIỂM SOÁT HÓA CHẤT VÀ ỨNG PHÓ SỰ CỐ\nPHẦN 5 – QUẢN LÝ LƯU TRỮ VÀ SỬ DỤNG HÓA CHẤT\nNội dung đào tạo\n• Nguyên tắc lưu trữ hóa chất\n• Phân khu hóa chất\n• Tương thích hóa chất\n• Secondary Containment\n• Quản lý tồn kho hóa chất\n• Kiểm soát cấp phát hóa chất\n• Quản lý hóa chất hết hạn\n• Kiểm soát nhà thầu hóa chất\nPHẦN 6 – KIỂM SOÁT PHƠI NHIỄM VÀ PPE\nNội dung đào tạo\n• Đường phơi nhiễm hóa chất\n• Kiểm soát phơi nhiễm\n• Thông gió và hút cục bộ\n• PPE (Personal Protective Equipment – Phương tiện bảo vệ cá nhân)\n• Eyewash và Safety Shower\n• Monitoring hóa chất\n• Kiểm tra sức khỏe nghề nghiệp\nPHẦN 7 – ỨNG PHÓ SỰ CỐ HÓA CHẤT\nNội dung đào tạo\n• Quy trình ứng phó sự cố hóa chất\n• Spill Control\n• Sự cố rò rỉ hóa chất\n• Cháy nổ hóa chất\n• Sơ cứu phơi nhiễm hóa chất\n• Emergency Response\n• Điều tra sự cố hóa chất\nNội dung thực hành\n• Thực hành ứng phó sự cố hóa chất\n• Thực hành xử lý spill kit\n• Thảo luận tình huống thực tế\nPHẦN 8 – XÂY DỰNG HỆ THỐNG QUẢN LÝ HÓA CHẤT\nNội dung đào tạo\n• Chemical Management Procedure\n• Chemical Register\n• Chemical Approval Process\n• Risk Assessment\n• Inspection Checklist\n• Chemical Audit\n• Tích hợp với ISO 14001 và ISO 45001\nPHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA\nNội dung thực hiện\n• Ôn tập nội dung đào tạo\n• Kiểm tra cuối khóa\n• Đánh giá năng lực học viên\n• Tổng kết chương trình đào tạo",
    "sort_order": 7
  },
  {
    "category_slug": "hse",
    "title": "Điều tra Sự cố và Phân tích Nguyên nhân Gốc rễ (Root Cause Analysis – RCA)",
    "description": "NGÀY 1 – TỔNG QUAN ĐIỀU TRA SỰ CỐ VÀ PHÂN TÍCH NGUYÊN NHÂN\nPHẦN 1 – TỔNG QUAN VỀ ĐIỀU TRA SỰ CỐ VÀ RCA\nPHẦN 2 – QUY TRÌNH ĐIỀU TRA SỰ CỐ\nPHẦN 3 – CÁC PHƯƠNG PHÁP PHÂN TÍCH NGUYÊN NHÂN GỐC RỄ\nPHẦN 4 – HUMAN FACTOR VÀ HÀNH VI TRONG SỰ CỐ\nNGÀY 2 – THIẾT LẬP GIẢI PHÁP VÀ TRIỂN KHAI THỰC TẾ\nPHẦN 5 – THIẾT LẬP HÀNH ĐỘNG KHẮC PHỤC VÀ PHÒNG NGỪA\nPHẦN 6 – RCA TRONG CÁC HOẠT ĐỘNG DOANH NGHIỆP\nPHẦN 7 – XÂY DỰNG HỆ THỐNG ĐIỀU TRA SỰ CỐ TẠI DOANH NGHIỆP\nPHẦN 8 – WORKSHOP THỰC HÀNH RCA\nPHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA",
    "detail": "NGÀY 1 – TỔNG QUAN ĐIỀU TRA SỰ CỐ VÀ PHÂN TÍCH NGUYÊN NHÂN\nPHẦN 1 – TỔNG QUAN VỀ ĐIỀU TRA SỰ CỐ VÀ RCA\nNội dung đào tạo\n• Khái niệm Incident, Accident và Near Miss\n• Tầm quan trọng của điều tra sự cố\n• Chi phí ẩn của tai nạn và sự cố\n• Nguyên tắc điều tra sự cố\n• Khái niệm Root Cause Analysis\n• Direct Cause và Root Cause\n• Tư duy phòng ngừa và cải tiến liên tục\nPHẦN 2 – QUY TRÌNH ĐIỀU TRA SỰ CỐ\nNội dung đào tạo\n• Quy trình điều tra sự cố\n• Thu thập bằng chứng\n• Thu thập thông tin hiện trường\n• Phỏng vấn nhân chứng\n• Chụp hình và lưu hồ sơ\n• Xác định chuỗi sự kiện\n• Phân tích điều kiện và hành vi\n• Xây dựng Timeline sự cố\nPHẦN 3 – CÁC PHƯƠNG PHÁP PHÂN TÍCH NGUYÊN NHÂN GỐC RỄ\nNội dung đào tạo\n• 5 Why Analysis\n• Fishbone Diagram\n• Fault Tree Analysis\n• Barrier Analysis\n• Cause and Effect Analysis\n• Human Factor Analysis\nNội dung thực hành\n• Thực hành phân tích tình huống sự cố\n• Xây dựng Fishbone Diagram\n• Thực hành 5 Why\n• Thảo luận nhóm và trình bày kết quả\nPHẦN 4 – HUMAN FACTOR VÀ HÀNH VI TRONG SỰ CỐ\nNội dung đào tạo\n• Human Error\n• Unsafe Behavior\n• Unsafe Condition\n• Organizational Failure\n• Leadership Failure\n• Communication Failure\n• Work Pressure\n• Fatigue\n• Safety Culture\nNGÀY 2 – THIẾT LẬP GIẢI PHÁP VÀ TRIỂN KHAI THỰC TẾ\nPHẦN 5 – THIẾT LẬP HÀNH ĐỘNG KHẮC PHỤC VÀ PHÒNG NGỪA\nNội dung đào tạo\n• CAPA (Corrective and Preventive Action)\n• Thiết lập hành động hiệu quả\n• Kiểm soát tái diễn\n• Theo dõi hiệu quả hành động\n• Đánh giá tính bền vững của giải pháp\nNội dung thực hành\n• Thiết lập CAPA cho tình huống thực tế\n• Đánh giá hiệu lực hành động\n• Thảo luận nhóm\nPHẦN 6 – RCA TRONG CÁC HOẠT ĐỘNG DOANH NGHIỆP\nNội dung đào tạo\n• RCA trong tai nạn lao động\n• RCA trong lỗi chất lượng\n• RCA trong sự cố môi trường\n• RCA trong sự cố thiết bị\n• RCA trong Near Miss\n• RCA trong hoạt động sản xuất\nPHẦN 7 – XÂY DỰNG HỆ THỐNG ĐIỀU TRA SỰ CỐ TẠI DOANH NGHIỆP\nNội dung đào tạo\n• Xây dựng Incident Investigation Procedure\n• Thiết lập Incident Reporting System\n• Thiết lập RCA Team\n• Quản lý hồ sơ điều tra\n• Theo dõi xu hướng sự cố\n• Tích hợp RCA với ISO 45001 và ISO 9001\nPHẦN 8 – WORKSHOP THỰC HÀNH RCA\nNội dung thực hiện\n• Phân tích Case Study thực tế\n• Điều tra tình huống mô phỏng\n• Xây dựng Timeline sự cố\n• Thực hiện RCA theo nhóm\n• Trình bày kết quả và coaching\nPHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA\nNội dung thực hiện\n• Ôn tập nội dung đào tạo\n• Kiểm tra cuối khóa\n• Đánh giá năng lực học viên\n• Tổng kết chương trình đào tạo",
    "sort_order": 8
  },
  {
    "category_slug": "hse",
    "title": "Phát triển năng lực cán bộ An toàn – Sức khỏe – Môi trường (HSE OFFICER)",
    "description": "NGÀY 01\nNỀN TẢNG QUẢN LÝ HSE VÀ ĐÁNH GIÁ RỦI RO\nChuyên đề 1\nTổng quan về HSE trong doanh nghiệp\nChuyên đề 2\nNhận diện mối nguy và đánh giá rủi ro\nChuyên đề 3\nKiểm tra hiện trường và Safety Patrol\nNGÀY 02\nQUẢN LÝ AN TOÀN – SỨC KHỎE NGHỀ NGHIỆP\nChuyên đề 4\nQuản lý máy móc thiết bị\nChuyên đề 5\nQuản lý hóa chất\nChuyên đề 6\nQuản lý sức khỏe nghề nghiệp\nChuyên đề 7\nĐiều tra tai nạn lao động\nNGÀY 03\nQUẢN LÝ MÔI TRƯỜNG VÀ KỸ NĂNG HSE OFFICER\nChuyên đề 8\nQuản lý môi trường\nChuyên đề 9\nỨng phó khẩn cấp\nChuyên đề 10\nKỹ năng chuyên nghiệp của HSE Officer",
    "detail": "NGÀY 01\nNỀN TẢNG QUẢN LÝ HSE VÀ ĐÁNH GIÁ RỦI RO\nChuyên đề 1\nTổng quan về HSE trong doanh nghiệp\nNội dung đào tạo:\n• Vai trò của HSE Officer.\n• Trách nhiệm pháp lý của doanh nghiệp.\n• Trách nhiệm của cán bộ HSE.\n• Hệ thống quản lý HSE hiện đại.\n• Tổng quan ISO 14001.\n• Tổng quan ISO 45001.\n• Tổng quan SMETA.\n• Tổng quan BSCI.\n• Các yêu cầu khách hàng thường gặp.\nChuyên đề 2\nNhận diện mối nguy và đánh giá rủi ro\nNội dung đào tạo:\n• Khái niệm mối nguy.\n• Khái niệm rủi ro.\n• Phân loại mối nguy.\nBao gồm:\n• Mối nguy cơ học.\n• Mối nguy điện.\n• Mối nguy hóa chất.\n• Mối nguy tiếng ồn.\n• Mối nguy bụi.\n• Mối nguy nhiệt.\n• Mối nguy công thái học.\n• Mối nguy giao thông nội bộ.\nThực hành:\n• Xây dựng bảng nhận diện mối nguy.\n• Xây dựng ma trận đánh giá rủi ro.\n• Thực hành đánh giá rủi ro thực tế.\nChuyên đề 3\nKiểm tra hiện trường và Safety Patrol\nNội dung đào tạo:\n• Phương pháp kiểm tra hiện trường.\n• Quan sát hành vi không an toàn.\n• Điều kiện không an toàn.\n• Kỹ năng giao tiếp với người lao động.\n• Kỹ năng ghi nhận bằng chứng.\n• Kỹ năng lập biên bản.\nThực hành:\n• Safety Walk.\n• Safety Observation.\n• Near Miss Reporting.\nNGÀY 02\nQUẢN LÝ AN TOÀN – SỨC KHỎE NGHỀ NGHIỆP\nChuyên đề 4\nQuản lý máy móc thiết bị\nNội dung đào tạo:\n• Kiểm soát thiết bị có yêu cầu nghiêm ngặt.\n• Kiểm định thiết bị.\n• Bảo trì phòng ngừa.\n• Khóa và gắn thẻ (LOTO).\n• Che chắn máy.\n• An toàn điện.\nChuyên đề 5\nQuản lý hóa chất\nNội dung đào tạo:\n• Hệ thống GHS.\n• Nhãn hóa chất.\n• Phiếu SDS.\n• Đánh giá rủi ro hóa chất.\n• Kho hóa chất.\n• Ứng phó tràn đổ.\n• PPE đối với hóa chất.\nThực hành:\n• Kiểm tra nhãn hóa chất.\n• Đánh giá khu vực lưu trữ.\nChuyên đề 6\nQuản lý sức khỏe nghề nghiệp\nNội dung đào tạo:\n• Bệnh nghề nghiệp.\n• Quan trắc môi trường lao động.\n• Khám sức khỏe định kỳ.\n• Khám bệnh nghề nghiệp.\n• Quản lý lao động nữ.\n• Quản lý lao động chưa thành niên.\nChuyên đề 7\nĐiều tra tai nạn lao động\nNội dung đào tạo:\n• Khái niệm tai nạn lao động.\n• Near Miss.\n• Incident.\n• Root Cause Analysis.\nCác công cụ:\n• 5 Why.\n• Fishbone Diagram.\n• Corrective Action Plan.\nThực hành:\n• Phân tích tình huống thực tế.\n• Lập báo cáo điều tra.\nNGÀY 03\nQUẢN LÝ MÔI TRƯỜNG VÀ KỸ NĂNG HSE OFFICER\nChuyên đề 8\nQuản lý môi trường\nNội dung đào tạo:\n• Khía cạnh môi trường.\n• Tác động môi trường.\n• Chất thải nguy hại.\n• Chất thải thông thường.\n• Nước thải.\n• Khí thải.\n• Tiếng ồn.\n• Tiết kiệm năng lượng.\nThực hành:\n• Nhận diện khía cạnh môi trường.\n• Đánh giá mức độ tác động.\nChuyên đề 9\nỨng phó khẩn cấp\nNội dung đào tạo:\n• Cháy nổ.\n• Tràn đổ hóa chất.\n• Tai nạn lao động.\n• Mất điện.\n• Thiên tai.\nThực hành:\n• Xây dựng kịch bản ứng phó.\n• Tổ chức diễn tập.\nChuyên đề 10\nKỹ năng chuyên nghiệp của HSE Officer\nNội dung đào tạo:\n• Kỹ năng đào tạo nội bộ.\n• Kỹ năng thuyết trình.\n• Kỹ năng viết báo cáo.\n• Kỹ năng lập KPI.\n• Kỹ năng xây dựng chương trình HSE.\n• Kỹ năng làm việc với cơ quan chức năng.\n• Kỹ năng làm việc với khách hàng đánh giá.",
    "sort_order": 9
  },
  {
    "category_slug": "hse",
    "title": "LÃNH ĐẠO AN TOÀN – XÂY DỰNG VĂN HÓA AN TOÀN VÀ QUẢN LÝ RỦI RO THẢM HỌA",
    "description": "NGÀY 01\nPROCESS SAFETY MANAGEMENT (PSM)\nLÃNH ĐẠO VÀ QUẢN TRỊ RỦI RO THẢM HỌA\nChuyên đề 1\nBài học từ các thảm họa công nghiệp trên thế giới\nPhân tích các vụ việc điển hình:\nChuyên đề 2\nTổng quan Process Safety Management (PSM)\nChuyên đề 3\nVai trò lãnh đạo trong quản lý rủi ro thảm họa\nNGÀY 02\nXÂY DỰNG VĂN HÓA AN TOÀN\nChuyên đề 4\nSafety Culture – Văn hóa an toàn là gì?\nChuyên đề 5\nHành vi lãnh đạo ảnh hưởng đến văn hóa an toàn\nChuyên đề 6\nTrách nhiệm giải trình về an toàn (Safety Accountability)\nNGÀY 03\nTỪ CHIẾN LƯỢC ĐẾN HÀNH ĐỘNG\nChuyên đề 7\nCác công cụ lãnh đạo an toàn hiện đại\nChuyên đề 8\nXây dựng chiến lược văn hóa an toàn\nChuyên đề 9\nCam kết lãnh đạo và kế hoạch hành động",
    "detail": "NGÀY 01\nPROCESS SAFETY MANAGEMENT (PSM)\nLÃNH ĐẠO VÀ QUẢN TRỊ RỦI RO THẢM HỌA\nChuyên đề 1\nBài học từ các thảm họa công nghiệp trên thế giới\nPhân tích các vụ việc điển hình:\n• Bhopal – Ấn Độ.\n• Piper Alpha – Vương quốc Anh.\n• Texas City – Hoa Kỳ.\n• Deepwater Horizon.\n• Beirut Explosion.\n• Các sự cố cháy nổ lớn tại Việt Nam.\nNội dung trọng tâm\n• Vì sao doanh nghiệp thất bại?\n• Vai trò của lãnh đạo trong các thảm họa.\n• Các dấu hiệu cảnh báo bị bỏ qua.\n• Chi phí thực sự của một tai nạn nghiêm trọng.\nChuyên đề 2\nTổng quan Process Safety Management (PSM)\nNội dung:\n• Khái niệm PSM.\n• Sự khác biệt giữa:\nAn toàn nghề nghiệp An toàn quá trình\nTai nạn cá nhân Sự cố thảm họa\nTác động cục bộ Tác động quy mô lớn\nMất ngày công Mất nhà máy\nChấn thương Tử vong hàng loạt\nCác yếu tố cốt lõi của PSM\n• Leadership Commitment.\n• Risk Management.\n• Asset Integrity.\n• Management of Change.\n• Emergency Preparedness.\n• Incident Investigation.\n• Process Safety Information.\nChuyên đề 3\nVai trò lãnh đạo trong quản lý rủi ro thảm họa\nNội dung:\n• Risk Governance.\n• Risk Appetite.\n• Risk Ownership.\n• Leadership Visibility.\n• Safety Accountability.\nWorkshop\nĐánh giá mức độ trưởng thành của hệ thống PSM hiện tại.\nNGÀY 02\nXÂY DỰNG VĂN HÓA AN TOÀN\nChuyên đề 4\nSafety Culture – Văn hóa an toàn là gì?\nNội dung:\n• Các cấp độ trưởng thành văn hóa an toàn.\n• Mô hình Bradley Curve.\n• Mô hình Hudson Safety Culture.\n• Từ \"Compliance\" đến \"Commitment\".\nĐánh giá hiện trạng\nDoanh nghiệp đang ở cấp độ nào?\n• Reactive.\n• Dependent.\n• Independent.\n• Interdependent.\nChuyên đề 5\nHành vi lãnh đạo ảnh hưởng đến văn hóa an toàn\nNội dung:\n• Những hành vi lãnh đạo tạo nên văn hóa an toàn.\n• Những hành vi lãnh đạo phá hủy văn hóa an toàn.\n• Quyền lực của người quản lý tuyến đầu.\n• Leadership Walkthrough.\n• Safety Gemba Walk.\nWorkshop\nTự đánh giá phong cách lãnh đạo an toàn của từng học viên.\nChuyên đề 6\nTrách nhiệm giải trình về an toàn (Safety Accountability)\nNội dung:\n• Khác biệt giữa Responsibility và Accountability.\n• Trách nhiệm của Tổng Giám đốc.\n• Trách nhiệm của Giám đốc nhà máy.\n• Trách nhiệm của Quản lý cấp trung.\n• Trách nhiệm của Giám sát.\nXây dựng:\n• Safety Accountability Matrix.\n• Safety KPI Cascade.\nNGÀY 03\nTỪ CHIẾN LƯỢC ĐẾN HÀNH ĐỘNG\nChuyên đề 7\nCác công cụ lãnh đạo an toàn hiện đại\nNội dung:\n• Near Miss Management.\n• Safety Observation.\n• Life Saving Rules.\n• Safety Walk.\n• Management Safety Tour.\n• Leadership Field Visit.\n• High Risk Activity Review.\nXây dựng hệ thống KPI chủ động\nBao gồm:\n• Near Miss Reporting Rate.\n• Safety Walk Completion.\n• Action Closure Rate.\n• Training Completion Rate.\n• High Risk Audit Score.\nChuyên đề 8\nXây dựng chiến lược văn hóa an toàn\nNội dung:\n• Safety Vision.\n• Safety Mission.\n• Safety Strategic Plan.\n• Communication Campaign.\n• Employee Engagement.\nWorkshop\nXây dựng Roadmap Văn hóa An toàn 12 tháng.\nChuyên đề 9\nCam kết lãnh đạo và kế hoạch hành động\nThảo luận:\n• Các rào cản hiện tại.\n• Các cơ hội cải tiến.\n• Các mục tiêu ưu tiên.\nKết quả cuối khóa\nMỗi lãnh đạo xây dựng:\n• Cam kết an toàn cá nhân.\n• Kế hoạch hành động 90 ngày.\n• Kế hoạch cải tiến 12 tháng.\n• Chương trình hiện diện lãnh đạo tại hiện trường.",
    "sort_order": 10
  },
  {
    "category_slug": "hse",
    "title": "XÂY DỰNG VĂN HÓA AN TOÀN VÀ QUẢN LÝ RỦI RO CHỦ ĐỘNG CHO QUẢN LÝ VỀ AN TOÀN",
    "description": "NGÀY 01\nXÂY DỰNG VĂN HÓA AN TOÀN VÀ HỆ THỐNG NEAR MISS\nChuyên đề 1\nVai trò lãnh đạo trong quản lý an toàn\nChuyên đề 2\nQuản lý Near Miss\nChuyên đề 3\nĐiều tra Near Miss\nNGÀY 02\nBEHAVIOR BASED SAFETY (BBS)\nChuyên đề 4\nTổng quan BBS\nChuyên đề 5\nThiết kế chương trình BBS\nChuyên đề 6\nKỹ năng Safety Coaching\nNGÀY 03\nLIFE SAVING RULES (LSR) VÀ WSFI\nChuyên đề 7\nLife Saving Rules\nChuyên đề 8\nWorkplace Safety Field Inspection (WSFI)\nNGÀY 04\nJOB HAZARD ANALYSIS (JHA)\nChuyên đề 9\nPhân tích công việc và nhận diện nguy cơ\nChuyên đề 10\nThực hành JHA\nChuyên đề 11\nPermit To Work liên kết JHA\nNGÀY 05\nĐÁNH GIÁ RỦI RO (DGRR) VÀ XÂY DỰNG CHIẾN LƯỢC HSE\nChuyên đề 12\nĐánh giá rủi ro nâng cao\nChuyên đề 13\nThiết lập KPI an toàn chủ động\nChuyên đề 14\nXây dựng Roadmap HSE 12 tháng",
    "detail": "NGÀY 01\nXÂY DỰNG VĂN HÓA AN TOÀN VÀ HỆ THỐNG NEAR MISS\nChuyên đề 1\nVai trò lãnh đạo trong quản lý an toàn\nNội dung đào tạo:\n• Từ quản lý an toàn sang lãnh đạo an toàn.\n• Mô hình Bradley Curve.\n• Safety Culture Maturity.\n• Các cấp độ trưởng thành văn hóa an toàn.\n• Vai trò của lãnh đạo tuyến đầu.\nChuyên đề 2\nQuản lý Near Miss\nNội dung đào tạo:\n• Khái niệm Near Miss.\n• Mối liên hệ giữa Near Miss và tai nạn.\n• Mô hình Heinrich Pyramid.\n• Các loại Near Miss phổ biến.\n• Quy trình báo cáo Near Miss.\nWorkshop\nXây dựng:\n• Quy trình Near Miss.\n• Phiếu báo cáo Near Miss.\n• KPI Near Miss.\n• Chương trình thưởng khuyến khích báo cáo.\nChuyên đề 3\nĐiều tra Near Miss\nCông cụ áp dụng:\n• 5 Why.\n• Fishbone Diagram.\n• Root Cause Analysis.\n• Corrective Action Plan.\nThực hành\nĐiều tra 05 tình huống Near Miss thực tế.\nNGÀY 02\nBEHAVIOR BASED SAFETY (BBS)\nChuyên đề 4\nTổng quan BBS\nNội dung:\n• Hành vi ảnh hưởng đến tai nạn như thế nào.\n• Mô hình ABC.\n• Human Factors.\n• Unsafe Act.\n• Unsafe Condition.\nChuyên đề 5\nThiết kế chương trình BBS\nNội dung:\n• Xác định Critical Behaviors.\n• Xây dựng Checklist BBS.\n• Phân công Observer.\n• Phương pháp thu thập dữ liệu.\nWorkshop\nThiết kế:\n• BBS Observation Card.\n• BBS KPI.\n• Dashboard BBS.\nChuyên đề 6\nKỹ năng Safety Coaching\nNội dung:\n• Quan sát hành vi.\n• Phản hồi tích cực.\n• Coaching tại hiện trường.\n• Giao tiếp với người lao động.\nThực hành\nRole Play BBS Coaching.\nNGÀY 03\nLIFE SAVING RULES (LSR) VÀ WSFI\nChuyên đề 7\nLife Saving Rules\nNội dung:\n• Khái niệm Life Saving Rules.\n• Các hành vi có nguy cơ tử vong.\n• Các nguyên nhân gây tai nạn nghiêm trọng.\nXây dựng bộ LSR\nBao gồm:\n• Khóa và gắn thẻ.\n• Làm việc trên cao.\n• Không gian hạn chế.\n• Điện.\n• Xe nâng.\n• Hóa chất.\n• Thiết bị nâng.\nWorkshop\nXây dựng:\n• Bộ 10 – 12 Life Saving Rules cho nhà máy.\nChuyên đề 8\nWorkplace Safety Field Inspection (WSFI)\nNội dung:\n• Khác biệt giữa Audit và Inspection.\n• Phương pháp WSFI.\n• Kỹ thuật quan sát hiện trường.\n• Kỹ năng phát hiện nguy cơ.\nWorkshop\nXây dựng:\n• Checklist WSFI.\n• Lịch WSFI.\n• KPI WSFI.\nNGÀY 04\nJOB HAZARD ANALYSIS (JHA)\nChuyên đề 9\nPhân tích công việc và nhận diện nguy cơ\nNội dung:\n• Khái niệm JHA.\n• Khi nào cần JHA.\n• Các bước thực hiện JHA.\nChuyên đề 10\nThực hành JHA\nThực hiện JHA cho:\n• Xe nâng.\n• Làm việc trên cao.\n• Bảo trì máy.\n• Hàn cắt.\n• Hóa chất.\n• Không gian hạn chế.\nWorkshop\nXây dựng:\n• Biểu mẫu JHA.\n• Quy trình JHA.\n• Ma trận phê duyệt JHA.\nChuyên đề 11\nPermit To Work liên kết JHA\nNội dung:\n• Giấy phép làm việc.\n• Kiểm soát công việc nguy hiểm.\n• Trách nhiệm các bên.\nNGÀY 05\nĐÁNH GIÁ RỦI RO (DGRR) VÀ XÂY DỰNG CHIẾN LƯỢC HSE\nChuyên đề 12\nĐánh giá rủi ro nâng cao\nNội dung:\n• ISO 45001 Risk Based Thinking.\n• Ma trận rủi ro.\n• HIRA (Hazard Identification and Risk Assessment).\n• ALARP Principle.\n• Risk Register.\nChuyên đề 13\nThiết lập KPI an toàn chủ động\nKPI đề xuất:\n• Near Miss Rate.\n• BBS Observation.\n• JHA Completion.\n• WSFI Closure Rate.\n• Action Closure Rate.\n• Safety Training Completion.\nChuyên đề 14\nXây dựng Roadmap HSE 12 tháng\nWorkshop nhóm:\nXây dựng:\n• Kế hoạch Near Miss.\n• Kế hoạch BBS.\n• Kế hoạch JHA.\n• Kế hoạch WSFI.\n• Kế hoạch đào tạo.\n• Kế hoạch cải tiến văn hóa an toàn.",
    "sort_order": 11
  },
  {
    "category_slug": "hse",
    "title": "XÂY DỰNG HỆ THỐNG QUẢN LÝ LAO ĐỘNG VÀ TRÁCH NHIỆM XÃ HỘI THEO YÊU CẦU CASCALE",
    "description": "GIAI ĐOẠN 1\nKHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2\nĐÁNH GIÁ THỰC TRẠNG (GAP ASSESSMENT)\nGIAI ĐOẠN 3\nXÂY DỰNG HỆ THỐNG HIGG FSLM\nGIAI ĐOẠN 4\nTRIỂN KHAI VÀ ĐÀO TẠO CHUYÊN SÂU\nGIAI ĐOẠN 5\nTỰ ĐÁNH GIÁ HIGG FSLM\nGIAI ĐOẠN 6\nPRE-ASSESSMENT VÀ CHUẨN BỊ ĐÁNH GIÁ KHÁCH HÀNG",
    "detail": "GIAI ĐOẠN 1\nKHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nNội dung thực hiện\n• Tổng quan Cascale và Higg Index.\n• Tổng quan Higg FSLM.\n• Mục tiêu và lợi ích của Higg FSLM.\n• Cấu trúc đánh giá Higg FSLM.\n• Vai trò của các bộ phận.\n• Thành lập nhóm triển khai.\n• Xây dựng kế hoạch thực hiện.\nĐầu ra\n• Kế hoạch triển khai.\n• Hồ sơ đào tạo nhận thức.\n• Ban triển khai Higg FSLM.\nGIAI ĐOẠN 2\nĐÁNH GIÁ THỰC TRẠNG (GAP ASSESSMENT)\nNội dung thực hiện\nĐánh giá hiện trạng hệ thống quản lý lao động và trách nhiệm xã hội:\nQuản trị và hệ thống quản lý\n• Chính sách.\n• Mục tiêu.\n• Trách nhiệm.\n• Truyền thông.\n• Đánh giá nội bộ.\nTuyển dụng và lao động\n• Tuyển dụng.\n• Hợp đồng lao động.\n• Hồ sơ lao động.\n• Lao động thời vụ.\n• Lao động chưa thành niên.\nTiền lương và phúc lợi\n• Lương tối thiểu.\n• Làm thêm giờ.\n• Phụ cấp.\n• Phúc lợi.\n• Bảo hiểm.\nThời giờ làm việc\n• Chấm công.\n• Tăng ca.\n• Nghỉ phép.\n• Ngày nghỉ.\nQuan hệ lao động\n• Khiếu nại.\n• Đối thoại.\n• Công đoàn.\n• Đại diện người lao động.\nSức khỏe và an toàn nghề nghiệp\n• Đánh giá rủi ro.\n• PPE.\n• Hóa chất.\n• Máy móc thiết bị.\n• PCCC.\n• Ứng phó khẩn cấp.\nĐầu ra\n• Báo cáo Gap Assessment.\n• Danh mục điểm chưa phù hợp.\n• Kế hoạch cải tiến.\nGIAI ĐOẠN 3\nXÂY DỰNG HỆ THỐNG HIGG FSLM\nNội dung thực hiện\nXây dựng và chuẩn hóa:\nChính sách\n• Chính sách trách nhiệm xã hội.\n• Chính sách nhân quyền.\n• Chính sách lao động.\n• Chính sách chống phân biệt đối xử.\n• Chính sách chống quấy rối.\n• Chính sách an toàn sức khỏe nghề nghiệp.\nQuy trình\n• Quy trình tuyển dụng.\n• Quy trình quản lý hồ sơ nhân sự.\n• Quy trình khiếu nại.\n• Quy trình đối thoại người lao động.\n• Quy trình đánh giá rủi ro.\n• Quy trình quản lý nhà thầu.\n• Quy trình quản lý nhà cung cấp.\n• Quy trình điều tra sự cố.\n• Quy trình hành động khắc phục.\nĐầu ra\n• Bộ tài liệu Higg FSLM.\n• Bộ quy trình quản lý lao động.\n• Bộ biểu mẫu quản lý.\nGIAI ĐOẠN 4\nTRIỂN KHAI VÀ ĐÀO TẠO CHUYÊN SÂU\nNội dung thực hiện\nMô-đun 1\nQuản lý lao động\n• Tuyển dụng.\n• Hợp đồng lao động.\n• Hồ sơ nhân sự.\n• Lao động chưa thành niên.\nMô-đun 2\nTiền lương và thời giờ làm việc\n• Kiểm soát bảng công.\n• Kiểm soát bảng lương.\n• Quản lý tăng ca.\n• Phúc lợi.\nMô-đun 3\nSức khỏe và an toàn nghề nghiệp\n• Đánh giá rủi ro.\n• PPE.\n• Hóa chất.\n• Tai nạn lao động.\n• PCCC.\nMô-đun 4\nQuan hệ lao động\n• Khiếu nại.\n• Đối thoại.\n• Truyền thông nội bộ.\nĐầu ra\n• Hồ sơ đào tạo.\n• Hồ sơ triển khai.\n• Hồ sơ vận hành thực tế.\nGIAI ĐOẠN 5\nTỰ ĐÁNH GIÁ HIGG FSLM\nNội dung thực hiện\n• Hướng dẫn thực hiện Self-Assessment.\n• Thu thập bằng chứng.\n• Đánh giá mức độ trưởng thành.\n• Chấm điểm theo từng phần.\n• Phân tích kết quả.\n• Xây dựng kế hoạch cải tiến.\nĐầu ra\n• Báo cáo Self-Assessment.\n• Bảng điểm Higg FSLM.\n• Kế hoạch cải tiến.\nGIAI ĐOẠN 6\nPRE-ASSESSMENT VÀ CHUẨN BỊ ĐÁNH GIÁ KHÁCH HÀNG\nNội dung thực hiện\n• Đánh giá thử toàn bộ hệ thống.\n• Phỏng vấn người lao động.\n• Kiểm tra hồ sơ.\n• Kiểm tra hiện trường.\n• Rà soát điểm chưa phù hợp.\n• Hướng dẫn khắc phục.\n• Chuẩn bị hồ sơ khách hàng.\nĐầu ra\n• Báo cáo Pre-Assessment.\n• Danh mục hồ sơ sẵn sàng đánh giá.\n• Kế hoạch hành động cuối cùng.",
    "sort_order": 12
  },
  {
    "category_slug": "hse",
    "title": "XÂY DỰNG TẦM NHÌN – SỨ MỆNH – CHIẾN LƯỢC HSE\nTỪ QUẢN LÝ HSE ĐẾN LÃNH ĐẠO HSE XUẤT SẮC",
    "description": "PHẦN 1\nHSE TRONG CHIẾN LƯỢC PHÁT TRIỂN DOANH NGHIỆP\nChuyên đề 1\nTừ HSE Compliance đến HSE Excellence\nChuyên đề 2\nĐánh giá mức độ trưởng thành của hệ thống HSE\nPHẦN 2\nXÂY DỰNG TẦM NHÌN HSE\nChuyên đề 3\nHSE Vision – Tầm nhìn HSE\nPHẦN 3\nXÂY DỰNG SỨ MỆNH HSE\nChuyên đề 4\nHSE Mission – Sứ mệnh HSE\nPHẦN 4\nXÂY DỰNG GIÁ TRỊ CỐT LÕI HSE\nChuyên đề 5\nHSE Core Values\nPHẦN 5\nXÂY DỰNG CHIẾN LƯỢC HSE\nChuyên đề 6\nHSE Strategic Planning\nChuyên đề 7\nXác định các trụ cột chiến lược\nPHẦN 6\nXÂY DỰNG KPI VÀ MỤC TIÊU HSE\nChuyên đề 8\nThiết lập mục tiêu HSE\nPHẦN 7\nXÂY DỰNG ROADMAP HSE 3 NĂM\nChuyên đề 9\nHSE Transformation Roadmap\nPHẦN 8\nCAM KẾT LÃNH ĐẠO\nChuyên đề 10\nLeadership Commitment Workshop",
    "detail": "PHẦN 1\nHSE TRONG CHIẾN LƯỢC PHÁT TRIỂN DOANH NGHIỆP\nChuyên đề 1\nTừ HSE Compliance đến HSE Excellence\nNội dung:\n• Sự khác biệt giữa doanh nghiệp tuân thủ và doanh nghiệp xuất sắc về HSE.\n• Xu hướng quản trị HSE trên thế giới.\n• Vai trò của HSE trong ESG.\n• Vai trò của HSE trong phát triển bền vững.\n• Vai trò của HSE trong chuỗi cung ứng toàn cầu.\n• Chi phí hữu hình và vô hình của tai nạn lao động.\nWorkshop\nĐánh giá hiện trạng HSE của doanh nghiệp.\nChuyên đề 2\nĐánh giá mức độ trưởng thành của hệ thống HSE\nMô hình đánh giá:\n• Reactive.\n• Dependent.\n• Independent.\n• Interdependent.\nWorkshop\nXác định doanh nghiệp đang ở cấp độ nào.\nXác định khoảng cách cần cải thiện.\nPHẦN 2\nXÂY DỰNG TẦM NHÌN HSE\nChuyên đề 3\nHSE Vision – Tầm nhìn HSE\nNội dung:\n• Khái niệm tầm nhìn.\n• Vai trò của tầm nhìn.\n• Đặc điểm của một tầm nhìn hiệu quả.\n• Tầm nhìn HSE của các tập đoàn hàng đầu thế giới.\nVí dụ tham khảo\n• Zero Harm.\n• Everyone Goes Home Safe Every Day.\n• Safety is Our Value.\nWorkshop\nXây dựng dự thảo tầm nhìn HSE cho doanh nghiệp.\nĐầu ra\nBản dự thảo Tầm nhìn HSE.\nPHẦN 3\nXÂY DỰNG SỨ MỆNH HSE\nChuyên đề 4\nHSE Mission – Sứ mệnh HSE\nNội dung:\n• Khái niệm sứ mệnh.\n• Vai trò của sứ mệnh.\n• Cách xây dựng sứ mệnh tạo động lực.\nWorkshop\nXác định:\n• Đối tượng được bảo vệ.\n• Giá trị doanh nghiệp tạo ra.\n• Cam kết đối với người lao động.\n• Cam kết đối với cộng đồng.\nĐầu ra\nBản dự thảo Sứ mệnh HSE.\nPHẦN 4\nXÂY DỰNG GIÁ TRỊ CỐT LÕI HSE\nChuyên đề 5\nHSE Core Values\nNội dung:\nCác giá trị cốt lõi thường gặp:\n• Safety First.\n• Respect for People.\n• Accountability.\n• Integrity.\n• Continuous Improvement.\n• Zero Tolerance for Unsafe Acts.\nWorkshop\nXây dựng bộ giá trị cốt lõi HSE riêng của doanh nghiệp.\nĐầu ra\nBộ giá trị cốt lõi HSE.\nPHẦN 5\nXÂY DỰNG CHIẾN LƯỢC HSE\nChuyên đề 6\nHSE Strategic Planning\nNội dung:\n• Khái niệm chiến lược HSE.\n• Chiến lược 3 năm.\n• Chiến lược 5 năm.\n• Mô hình Balanced Scorecard cho HSE.\nChuyên đề 7\nXác định các trụ cột chiến lược\nWorkshop xây dựng:\nTrụ cột 1\nLãnh đạo và văn hóa an toàn.\nTrụ cột 2\nQuản lý rủi ro.\nTrụ cột 3\nNăng lực và nhận thức.\nTrụ cột 4\nHiệu suất môi trường.\nTrụ cột 5\nSức khỏe nghề nghiệp.\nTrụ cột 6\nSố hóa và chuyển đổi HSE.\nĐầu ra\nBản đồ chiến lược HSE.\nPHẦN 6\nXÂY DỰNG KPI VÀ MỤC TIÊU HSE\nChuyên đề 8\nThiết lập mục tiêu HSE\nNội dung:\n• KPI dẫn dắt (Leading Indicators).\n• KPI kết quả (Lagging Indicators).\nVí dụ KPI\n• Near Miss Reporting.\n• BBS Observation.\n• JHA Completion.\n• Safety Walk.\n• WSFI.\n• Closure Rate.\n• LTIFR.\n• TRIR.\nWorkshop\nXây dựng KPI cho:\n• Ban lãnh đạo.\n• Quản lý.\n• HSE.\n• Sản xuất.\nPHẦN 7\nXÂY DỰNG ROADMAP HSE 3 NĂM\nChuyên đề 9\nHSE Transformation Roadmap\nNội dung:\nNăm 1\nXây nền tảng.\nNăm 2\nChuẩn hóa hệ thống.\nNăm 3\nVăn hóa an toàn.\nWorkshop\nXây dựng:\n• Mục tiêu năm.\n• Kế hoạch năm.\n• Dự án ưu tiên.\n• Ngân sách HSE.\nĐầu ra\nRoadmap HSE 3 năm.\nPHẦN 8\nCAM KẾT LÃNH ĐẠO\nChuyên đề 10\nLeadership Commitment Workshop\nBan lãnh đạo cùng xây dựng:\n• Tuyên bố cam kết HSE.\n• Cam kết trách nhiệm giải trình.\n• Chương trình Leadership Safety Walk.\n• Chương trình Safety Culture Campaign.",
    "sort_order": 13
  },
  {
    "category_slug": "carbon",
    "title": "Kiểm kê Khí nhà kính theo Nghị định 06/2022/NĐ-CP",
    "description": "",
    "detail": "",
    "sort_order": 14
  },
  {
    "category_slug": "carbon",
    "title": "Kiểm kê Khí nhà kính theo ISO 14064-1:2018",
    "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – XÁC ĐỊNH NGUỒN PHÁT THẢI VÀ THU THẬP DỮ LIỆU\nGIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG TÍNH TOÁN PHÁT THẢI\nGIAI ĐOẠN 4 – XÂY DỰNG HỆ THỐNG TÀI LIỆU VÀ BÁO CÁO\nGIAI ĐOẠN 5 – ĐÀO TẠO VÀ HOÀN THIỆN HỆ THỐNG",
    "detail": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nNội dung thực hiện\n• Thành lập Ban GHG\n• Xây dựng kế hoạch triển khai\n• Phân công vai trò và trách nhiệm\n• Đào tạo nhận thức ISO 14064-1:2018\n• Tổng quan về biến đổi khí hậu và khí nhà kính\n• Tổng quan Scope 1, Scope 2 và Scope 3\n• Tổng quan ESG và Carbon Management\nThời gian dự kiến\n02 ngày\nGIAI ĐOẠN 2 – XÁC ĐỊNH NGUỒN PHÁT THẢI VÀ THU THẬP DỮ LIỆU\nNội dung thực hiện\n• Xác định Organizational Boundary\n• Xác định Operational Boundary\n• Xác định nguồn phát thải Scope 1\n• Xác định nguồn phát thải Scope 2\n• Xác định nguồn phát thải Scope 3\n• Thiết lập danh mục nguồn phát thải\n• Thu thập Activity Data\n• Xây dựng Data Collection System\n• Đánh giá chất lượng dữ liệu\nThời gian dự kiến\n03 – 04 ngày\nGIAI ĐOẠN 3 – XÂY DỰNG HỆ THỐNG TÍNH TOÁN PHÁT THẢI\nNội dung thực hiện\n• Xây dựng phương pháp tính toán phát thải\n• Áp dụng Emission Factor\n• Thiết lập bảng tính GHG\n• Tính toán Scope 1\n• Tính toán Scope 2\n• Tính toán Scope 3\n• Kiểm tra và rà soát dữ liệu\n• Xây dựng hệ thống quản lý dữ liệu GHG\nThời gian dự kiến\n03 – 04 ngày\nGIAI ĐOẠN 4 – XÂY DỰNG HỆ THỐNG TÀI LIỆU VÀ BÁO CÁO\nNội dung thực hiện\n• Xây dựng GHG Procedure\n• Xây dựng SOP thu thập dữ liệu\n• Xây dựng Data Management Procedure\n• Xây dựng Risk and Opportunity Assessment\n• Xây dựng Quality Management Plan\n• Xây dựng báo cáo kiểm kê khí nhà kính\n• Xây dựng hồ sơ phục vụ xác minh\nThời gian dự kiến\n02 – 03 ngày\nGIAI ĐOẠN 5 – ĐÀO TẠO VÀ HOÀN THIỆN HỆ THỐNG\nNội dung thực hiện\n• Đào tạo đội ngũ thực hiện GHG\n• Đào tạo quản lý dữ liệu phát thải\n• Đào tạo kiểm soát chất lượng dữ liệu\n• Hướng dẫn chuẩn bị xác minh\n• Rà soát và hoàn thiện hệ thống\n• Coaching thực tế tại doanh nghiệp\nThời gian dự kiến\n02 ngày",
    "sort_order": 15
  },
  {
    "category_slug": "carbon",
    "title": "Hướng dẫn Định lượng Khí nhà kính theo ISO 14069:2013",
    "description": "",
    "detail": "",
    "sort_order": 16
  },
  {
    "category_slug": "carbon",
    "title": "Greenhouse Gas Protocol (GHG Protocol)",
    "description": "",
    "detail": "",
    "sort_order": 17
  },
  {
    "category_slug": "carbon",
    "title": "Tính toán Vết Carbon của Sản phẩm theo ISO 14067:2018",
    "description": "",
    "detail": "",
    "sort_order": 18
  },
  {
    "category_slug": "carbon",
    "title": "PAS 2060:2014 – Chứng nhận Trung hòa Carbon",
    "description": "",
    "detail": "",
    "sort_order": 19
  },
  {
    "category_slug": "carbon",
    "title": "ISO 14068-1:2023 – Trung hòa Carbon",
    "description": "",
    "detail": "",
    "sort_order": 20
  },
  {
    "category_slug": "carbon",
    "title": "Verified Carbon Standard (VCS) – Tiêu chuẩn Carbon đã được Xác minh",
    "description": "",
    "detail": "",
    "sort_order": 21
  },
  {
    "category_slug": "esg",
    "title": "Environmental, Social and Governance (ESG) – Môi trường, Xã hội và Quản trị",
    "description": "",
    "detail": "",
    "sort_order": 22
  },
  {
    "category_slug": "esg",
    "title": "Global Reporting Initiative (GRI) – Sáng kiến Báo cáo Toàn cầu",
    "description": "",
    "detail": "",
    "sort_order": 23
  },
  {
    "category_slug": "esg",
    "title": "QUẢN LÝ NGUỒN CUNG ỨNG RỪNG BỀN VỮNG VÀ TRÁCH NHIỆM",
    "description": "Chuyên đề 1\nTổng quan FSC\nChuyên đề 2\n10 Nguyên tắc FSC\nChuyên đề 3\nFSC và Phát triển bền vững\nChuyên đề 4\nFSC và Chuỗi cung ứng\nChuyên đề 5\nFSC và Doanh nghiệp",
    "detail": "Chuyên đề 1\nTổng quan FSC\n• Lịch sử hình thành FSC.\n• Cơ cấu tổ chức FSC.\n• Hệ thống chứng nhận FSC.\nChuyên đề 2\n10 Nguyên tắc FSC\nBao gồm:\n• Tuân thủ pháp luật.\n• Quyền người lao động.\n• Quyền cộng đồng địa phương.\n• Quan hệ với người dân bản địa.\n• Lợi ích từ rừng.\n• Giá trị môi trường.\n• Kế hoạch quản lý.\n• Giám sát.\n• Giá trị bảo tồn cao.\n• Thực hiện các hoạt động quản lý.\nChuyên đề 3\nFSC và Phát triển bền vững\n• ESG.\n• Kinh tế tuần hoàn.\n• Truy xuất nguồn gốc.\n• Bảo tồn đa dạng sinh học.\n• Quản lý tài nguyên thiên nhiên.\nChuyên đề 4\nFSC và Chuỗi cung ứng\n• Quản lý nhà cung cấp.\n• Truy xuất nguồn gốc.\n• Đánh giá rủi ro nguồn cung.\n• Tuyên bố FSC.\nChuyên đề 5\nFSC và Doanh nghiệp\n• Lợi ích thương mại.\n• Yêu cầu khách hàng quốc tế.\n• FSC và các thị trường xuất khẩu.\n• FSC và chiến lược phát triển bền vững.",
    "sort_order": 24
  },
  {
    "category_slug": "esg",
    "title": "Science Based Targets initiative (SBTi) – Sáng kiến Mục tiêu Dựa trên Cơ sở Khoa học",
    "description": "",
    "detail": "",
    "sort_order": 25
  },
  {
    "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
    "title": "HỆ THỐNG QUẢN LÝ CHUỖI HÀNH TRÌNH SẢN PHẨM FSC",
    "description": "GIAI ĐOẠN 1\nKHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2\nĐÁNH GIÁ THỰC TRẠNG (GAP ASSESSMENT)\nGIAI ĐOẠN 3\nXÂY DỰNG HỆ THỐNG FSC CoC\nGIAI ĐOẠN 4\nTRIỂN KHAI HỆ THỐNG VÀ ĐÀO TẠO CHUYÊN SÂU\nGIAI ĐOẠN 5\nĐÁNH GIÁ NỘI BỘ VÀ CHUẨN BỊ CHỨNG NHẬN",
    "detail": "GIAI ĐOẠN 1\nKHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nNội dung:\n• Tổng quan FSC.\n• Tổng quan FSC CoC.\n• Các loại chứng nhận FSC.\n• FSC 100%.\n• FSC Mix.\n• FSC Recycled.\n• Yêu cầu khách hàng và thị trường.\n• Vai trò và trách nhiệm các bộ phận.\n• Thành lập nhóm triển khai FSC.\nĐầu ra\n• Kế hoạch triển khai.\n• Hồ sơ đào tạo nhận thức.\n• Ban triển khai FSC.\nGIAI ĐOẠN 2\nĐÁNH GIÁ THỰC TRẠNG (GAP ASSESSMENT)\nNội dung:\n• Đánh giá hệ thống hiện tại.\n• Đánh giá nhà cung cấp.\n• Đánh giá quản lý kho.\n• Đánh giá truy xuất nguồn gốc.\n• Đánh giá quản lý hồ sơ.\n• Đánh giá hoạt động sản xuất.\n• Đánh giá ghi nhãn sản phẩm.\n• Đánh giá năng lực nhân sự.\nĐầu ra\n• Báo cáo GAP.\n• Danh mục điểm chưa phù hợp.\n• Kế hoạch hành động.\nGIAI ĐOẠN 3\nXÂY DỰNG HỆ THỐNG FSC CoC\nNội dung:\nXây dựng:\n• Chính sách FSC.\n• Mục tiêu FSC.\n• Quy trình mua hàng FSC.\n• Quy trình tiếp nhận nguyên liệu FSC.\n• Quy trình quản lý kho FSC.\n• Quy trình truy xuất nguồn gốc.\n• Quy trình sản xuất sản phẩm FSC.\n• Quy trình bán hàng FSC.\n• Quy trình quản lý tuyên bố FSC.\n• Quy trình quản lý nhãn FSC.\n• Quy trình kiểm soát hồ sơ.\n• Quy trình đánh giá nội bộ.\nĐầu ra\n• Bộ tài liệu FSC CoC.\n• Biểu mẫu FSC.\n• Danh mục hồ sơ kiểm soát.\nGIAI ĐOẠN 4\nTRIỂN KHAI HỆ THỐNG VÀ ĐÀO TẠO CHUYÊN SÂU\nNội dung:\n• Quản lý nhà cung cấp FSC.\n• Kiểm tra chứng chỉ FSC.\n• Kiểm soát hóa đơn và chứng từ FSC.\n• Kiểm soát tồn kho FSC.\n• Quản lý sản xuất sản phẩm FSC.\n• Quản lý tuyên bố FSC.\n• Quản lý nhãn FSC.\n• Truy xuất nguồn gốc sản phẩm.\nĐầu ra\n• Hồ sơ đào tạo.\n• Hồ sơ vận hành thực tế.\n• Hồ sơ truy xuất nguồn gốc.\nGIAI ĐOẠN 5\nĐÁNH GIÁ NỘI BỘ VÀ CHUẨN BỊ CHỨNG NHẬN\nNội dung:\n• Đào tạo đánh giá viên nội bộ.\n• Thực hiện đánh giá nội bộ.\n• Hành động khắc phục.\n• Xem xét của lãnh đạo.\n• Chuẩn bị đánh giá chứng nhận.\nĐầu ra\n• Báo cáo đánh giá nội bộ.\n• CAPA Plan.\n• Hồ sơ sẵn sàng chứng nhận",
    "sort_order": 26
  },
  {
    "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
    "title": "International Sustainability and Carbon Certification PLUS (ISCC PLUS)/International Sustainability and Carbon Certification EU (ISCC EU)/International Sustainability and Carbon Certification CORSIA (ISCC CORSIA)/Global Recycled Standard (GRS) – Tiêu chuẩn Tái chế Toàn cầu",
    "description": "",
    "detail": "",
    "sort_order": 27
  },
  {
    "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
    "title": "EN 15343 – Truy xuất nguồn gốc và xác định hàm lượng nhựa tái chế trong sản phẩm nhựa",
    "description": "",
    "detail": "",
    "sort_order": 28
  },
  {
    "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
    "title": "Cradle to Cradle Certified® – Tiêu chuẩn Thiết kế Sản phẩm Tuần hoàn",
    "description": "",
    "detail": "",
    "sort_order": 29
  },
  {
    "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
    "title": "Higg Facility Environmental Module (Higg FEM)",
    "description": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nGIAI ĐOẠN 2 – THU THẬP DỮ LIỆU VÀ XÂY DỰNG HỆ THỐNG QUẢN LÝ\nGIAI ĐOẠN 3 – TRIỂN KHAI CÁC MODULE HIGG FEM\nGIAI ĐOẠN 4 – ĐÀO TẠO VÀ TRIỂN KHAI THỰC TẾ\nGIAI ĐOẠN 5 – RÀ SOÁT VÀ HOÀN THIỆN HỆ THỐNG",
    "detail": "GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC\nNội dung thực hiện\n• Thành lập Ban Higg FEM\n• Xây dựng kế hoạch triển khai\n• Phân công vai trò và trách nhiệm\n• Đào tạo nhận thức Higg FEM\n• Tổng quan về Sustainability và ESG\n• Tổng quan cấu trúc Higg FEM\n• Tổng quan Verification Process\n• Hướng dẫn sử dụng nền tảng Higg FEM\nThời gian dự kiến\n02 ngày\nGIAI ĐOẠN 2 – THU THẬP DỮ LIỆU VÀ XÂY DỰNG HỆ THỐNG QUẢN LÝ\nNội dung thực hiện\n• Thu thập dữ liệu môi trường\n• Xây dựng hệ thống quản lý dữ liệu\n• Kiểm soát tài liệu và hồ sơ môi trường\n• Thiết lập KPI môi trường\n• Kiểm soát dữ liệu tiêu thụ năng lượng\n• Kiểm soát dữ liệu nước\n• Kiểm soát dữ liệu chất thải\n• Kiểm soát dữ liệu khí thải\n• Kiểm soát hóa chất\nThời gian dự kiến\n03 – 04 ngày\nGIAI ĐOẠN 3 – TRIỂN KHAI CÁC MODULE HIGG FEM\nNội dung thực hiện\nEnvironmental Management System\n• Chính sách môi trường\n• Mục tiêu môi trường\n• Legal Compliance\n• Internal Audit\n• Management Review\nEnergy and GHG\n• Energy Management\n• GHG Inventory\n• Energy KPI\n• Energy Saving Program\nWater Management\n• Water Consumption\n• Water Balance\n• Water Reduction Program\n• Wastewater Management\nWaste Management\n• Waste Inventory\n• Waste Segregation\n• Waste Tracking\n• Hazardous Waste Management\nAir Emission\n• Air Emission Inventory\n• Air Pollution Control\n• Monitoring and Reporting\nChemical Management\n• Chemical Inventory\n• SDS Management\n• Chemical Risk Assessment\n• Spill Response\nThời gian dự kiến\n03 – 04 ngày\nGIAI ĐOẠN 4 – ĐÀO TẠO VÀ TRIỂN KHAI THỰC TẾ\nNội dung thực hiện\n• Đào tạo đội ngũ thực hiện Higg FEM\n• Đào tạo quản lý dữ liệu môi trường\n• Đào tạo kiểm soát hồ sơ\n• Coaching hiện trường\n• Hướng dẫn nhập dữ liệu Higg FEM\n• Hướng dẫn chuẩn bị Verification\n• Theo dõi hiệu quả áp dụng thực tế\nThời gian dự kiến\n02 – 03 ngày\nGIAI ĐOẠN 5 – RÀ SOÁT VÀ HOÀN THIỆN HỆ THỐNG\nNội dung thực hiện\n• Rà soát dữ liệu Higg FEM\n• Kiểm tra hồ sơ môi trường\n• Kiểm tra hiện trường\n• Đánh giá mức độ đáp ứng Higg FEM\n• Theo dõi hành động cải tiến\n• Hoàn thiện hồ sơ Verification\n• Chuẩn bị tiếp đoàn Verification\nThời gian dự kiến\n02 ngày",
    "sort_order": 30
  },
  {
    "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
    "title": "Đánh giá Nhà cung cấp",
    "description": "NGÀY 1 – TỔNG QUAN VỀ ĐÁNH GIÁ NHÀ CUNG CẤP\nPHẦN 1 – TỔNG QUAN QUẢN LÝ NHÀ CUNG CẤP\nPHẦN 2 – HỆ THỐNG ĐÁNH GIÁ NHÀ CUNG CẤP\nPHẦN 3 – ĐÁNH GIÁ HỆ THỐNG NHÀ CUNG CẤP\nPHẦN 4 – KỸ NĂNG ĐÁNH GIÁ NHÀ CUNG CẤP\nNGÀY 2 – TRIỂN KHAI ĐÁNH GIÁ VÀ CẢI TIẾN NHÀ CUNG CẤP\nPHẦN 5 – ĐÁNH GIÁ HIỆN TRƯỜNG NHÀ CUNG CẤP\nPHẦN 6 – PHÂN TÍCH RỦI RO NHÀ CUNG CẤP\nPHẦN 7 – CAPA VÀ PHÁT TRIỂN NHÀ CUNG CẤP\nPHẦN 8 – XÂY DỰNG HỆ THỐNG QUẢN LÝ NHÀ CUNG CẤP\nPHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA",
    "detail": "NGÀY 1 – TỔNG QUAN VỀ ĐÁNH GIÁ NHÀ CUNG CẤP\nPHẦN 1 – TỔNG QUAN QUẢN LÝ NHÀ CUNG CẤP\nNội dung đào tạo\n• Vai trò của nhà cung cấp trong chuỗi cung ứng\n• Rủi ro trong chuỗi cung ứng\n• Supplier Lifecycle Management\n• Tiêu chí lựa chọn nhà cung cấp\n• Yêu cầu khách hàng và tiêu chuẩn quốc tế\n• Vai trò của Supplier Assessment\nPHẦN 2 – HỆ THỐNG ĐÁNH GIÁ NHÀ CUNG CẤP\nNội dung đào tạo\n• Supplier Evaluation Process\n• Supplier Approval Process\n• Supplier Risk Assessment\n• Supplier Classification\n• Supplier KPI\n• Approved Vendor List\nNội dung thực hành\n• Xây dựng tiêu chí đánh giá nhà cung cấp\n• Thiết lập bảng chấm điểm\n• Phân loại nhà cung cấp\nPHẦN 3 – ĐÁNH GIÁ HỆ THỐNG NHÀ CUNG CẤP\nNội dung đào tạo\n• Đánh giá hệ thống quản lý chất lượng\n• Đánh giá năng lực sản xuất\n• Đánh giá kiểm soát chất lượng\n• Đánh giá truy xuất nguồn gốc\n• Đánh giá năng lực giao hàng\n• Đánh giá quản lý kho\n• Đánh giá quản lý thay đổi\n• Đánh giá quản lý rủi ro\nPHẦN 4 – KỸ NĂNG ĐÁNH GIÁ NHÀ CUNG CẤP\nNội dung đào tạo\n• Kỹ năng phỏng vấn\n• Kỹ năng quan sát hiện trường\n• Kỹ năng đặt câu hỏi\n• Kỹ năng thu thập bằng chứng\n• Kỹ năng viết báo cáo đánh giá\n• Kỹ năng giao tiếp với nhà cung cấp\nNội dung thực hành\n• Thực hành phỏng vấn\n• Thực hành đánh giá tình huống\n• Workshop đánh giá nhà cung cấp\nNGÀY 2 – TRIỂN KHAI ĐÁNH GIÁ VÀ CẢI TIẾN NHÀ CUNG CẤP\nPHẦN 5 – ĐÁNH GIÁ HIỆN TRƯỜNG NHÀ CUNG CẤP\nNội dung đào tạo\n• Đánh giá hiện trường sản xuất\n• Đánh giá kiểm soát chất lượng\n• Đánh giá thiết bị và bảo trì\n• Đánh giá nhân sự và đào tạo\n• Đánh giá quản lý nguyên vật liệu\n• Đánh giá an toàn và môi trường\n• Đánh giá kiểm soát sản phẩm không phù hợp\nPHẦN 6 – PHÂN TÍCH RỦI RO NHÀ CUNG CẤP\nNội dung đào tạo\n• Supplier Risk Matrix\n• Quality Risk\n• Delivery Risk\n• Capacity Risk\n• Compliance Risk\n• ESG Risk\n• Business Continuity Risk\nNội dung thực hành\n• Đánh giá rủi ro nhà cung cấp\n• Thiết lập Supplier Risk Register\n• Thảo luận tình huống thực tế\nPHẦN 7 – CAPA VÀ PHÁT TRIỂN NHÀ CUNG CẤP\nNội dung đào tạo\n• Supplier CAPA Management\n• Root Cause Analysis\n• Theo dõi hành động cải tiến\n• Supplier Development Program\n• Supplier Improvement Plan\n• Supplier Performance Monitoring\nPHẦN 8 – XÂY DỰNG HỆ THỐNG QUẢN LÝ NHÀ CUNG CẤP\nNội dung đào tạo\n• Supplier Management Procedure\n• Supplier KPI Dashboard\n• Supplier Audit Checklist\n• Supplier Scorecard\n• Supplier Monitoring System\n• Tích hợp với ISO 9001, ISO 14001 và ISO 45001\nPHẦN 9 – TỔNG KẾT VÀ ĐÁNH GIÁ CUỐI KHÓA\nNội dung thực hiện\n• Ôn tập nội dung đào tạo\n• Kiểm tra cuối khóa\n• Đánh giá năng lực học viên\n• Tổng kết chương trình đào tạo",
    "sort_order": 31
  },
  {
    "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
    "title": "Social Accountability 8000 (SA8000) – Tiêu chuẩn Trách nhiệm Xã hội",
    "description": "",
    "detail": "",
    "sort_order": 32
  },
  {
    "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
    "title": "Sedex Members Ethical Trade Audit (SMETA)",
    "description": "",
    "detail": "",
    "sort_order": 33
  },
  {
    "category_slug": "chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan",
    "title": "Business Social Compliance Initiative (BSCI) – Bộ Tiêu chuẩn Tuân thủ Trách nhiệm Xã hội trong Kinh doanh",
    "description": "",
    "detail": "",
    "sort_order": 34
  },
  {
    "category_slug": "cong-trinh-xanh-va-san-pham-ben-vung",
    "title": "Leadership in Energy and Environmental Design (LEED) – Hệ thống Đánh giá Công trình Xanh",
    "description": "",
    "detail": "",
    "sort_order": 35
  },
  {
    "category_slug": "cong-trinh-xanh-va-san-pham-ben-vung",
    "title": "Environmental Product Declaration (EPD) – Tuyên bố Sản phẩm Môi trường",
    "description": "",
    "detail": "",
    "sort_order": 36
  }
]
JSON, true, 512, JSON_THROW_ON_ERROR);
    }
};

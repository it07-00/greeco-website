<?php

namespace App\Services;

use App\Filament\Resources\Contacts\ContactResource;
use App\Filament\Resources\CourseRegistrations\CourseRegistrationResource;
use App\Filament\Resources\Courses\CourseResource;
use App\Filament\Resources\DocumentResource;
use App\Filament\Resources\Posts\PostResource;
use App\Filament\Resources\Projects\ProjectResource;
use App\Filament\Resources\Services\ServiceResource;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Document;
use App\Models\EduQuiz\Question;
use App\Models\EduQuiz\Quiz;
use App\Models\EduQuiz\Result;
use App\Models\EduQuiz\Room;
use App\Models\Post;
use App\Models\Project;
use App\Models\RegistrationAttendee;
use App\Models\Service;
use Illuminate\Database\Eloquent\Builder;

class ActivityStatisticsService
{
    /**
     * @return list<array{
     *     label: string,
     *     group: string,
     *     url: string,
     *     today: int,
     *     week: int,
     *     month: int,
     *     year: int,
     *     total: int,
     *     highlight_label: string,
     *     highlight_tone: string
     * }>
     */
    public function getSummaryRows(): array
    {
        $now = now();
        $ranges = [
            'today_start' => $now->copy()->startOfDay()->toDateTimeString(),
            'today_end' => $now->copy()->endOfDay()->toDateTimeString(),
            'week_start' => $now->copy()->startOfWeek()->toDateTimeString(),
            'week_end' => $now->copy()->endOfWeek()->toDateTimeString(),
            'month_start' => $now->copy()->startOfMonth()->toDateTimeString(),
            'month_end' => $now->copy()->endOfMonth()->toDateTimeString(),
            'year_start' => $now->copy()->startOfYear()->toDateTimeString(),
            'year_end' => $now->copy()->endOfYear()->toDateTimeString(),
        ];

        $postStats = $this->summarizePeriods(Post::query(), $ranges);
        $publishedPosts = Post::query()->published()->count();

        $courseStats = $this->summarizePeriods(Course::query(), $ranges);
        $activeCourses = Course::query()->where('is_active', true)->count();

        $serviceStats = $this->summarizePeriods(Service::query(), $ranges);
        $activeServices = Service::query()->where('is_active', true)->count();

        $projectStats = $this->summarizePeriods(Project::query(), $ranges);
        $activeProjects = Project::query()->where('is_active', true)->count();

        $documentStats = $this->summarizePeriods(Document::query(), $ranges);
        $activeDocuments = Document::query()->where('is_active', true)->count();

        $registrationStats = $this->summarizePeriods(CourseRegistration::query(), $ranges);
        $newRegistrations = CourseRegistration::query()->where('status', 'new')->count();

        $attendeeStats = $this->summarizePeriods(RegistrationAttendee::query(), $ranges);

        $contactStats = $this->summarizePeriods(Contact::query(), $ranges);
        $unreadContacts = Contact::query()->where('is_read', false)->count();

        $questionStats = $this->summarizePeriods(Question::query(), $ranges);
        $quizStats = $this->summarizePeriods(Quiz::query(), $ranges);
        $roomStats = $this->summarizePeriods(Room::query(), $ranges);
        $openRooms = Room::query()->whereNull('finished_at')->count();
        $resultStats = $this->summarizePeriods(Result::query(), $ranges);

        return [
            $this->makeRow(
                label: 'Đăng ký khóa học',
                group: 'Đào tạo & Tuyển sinh',
                url: CourseRegistrationResource::getUrl('index'),
                stats: $registrationStats,
                highlightLabel: number_format($newRegistrations).' đăng ký mới',
                highlightTone: $newRegistrations > 0 ? 'warning' : 'success',
            ),
            $this->makeRow(
                label: 'Học viên đăng ký khóa học',
                group: 'Đào tạo & Tuyển sinh',
                url: CourseRegistrationResource::getUrl('index'),
                stats: $attendeeStats,
                highlightLabel: 'Tổng '.number_format($attendeeStats['total']).' học viên',
                highlightTone: 'info',
            ),
            $this->makeRow(
                label: 'Liên hệ khách hàng',
                group: 'Khách hàng',
                url: ContactResource::getUrl('index'),
                stats: $contactStats,
                highlightLabel: number_format($unreadContacts).' chưa đọc',
                highlightTone: $unreadContacts > 0 ? 'warning' : 'success',
            ),
            $this->makeRow(
                label: 'Phòng thi EduQuiz',
                group: 'EduQuiz (Trắc nghiệm)',
                url: route('eduquiz.staff.rooms'),
                stats: $roomStats,
                highlightLabel: number_format($openRooms).' phòng đang mở',
                highlightTone: $openRooms > 0 ? 'success' : 'gray',
            ),
            $this->makeRow(
                label: 'Lượt nộp bài EduQuiz',
                group: 'EduQuiz (Trắc nghiệm)',
                url: route('eduquiz.staff.results'),
                stats: $resultStats,
                highlightLabel: number_format($quizStats['total']).' bộ đề • '.number_format($questionStats['total']).' câu hỏi',
                highlightTone: 'info',
            ),
            $this->makeRow(
                label: 'Khóa học đào tạo',
                group: 'Nội dung',
                url: CourseResource::getUrl('index'),
                stats: $courseStats,
                highlightLabel: number_format($activeCourses).' đang hiển thị',
                highlightTone: 'info',
            ),
            $this->makeRow(
                label: 'Bài viết / Tin tức',
                group: 'Nội dung',
                url: PostResource::getUrl('index'),
                stats: $postStats,
                highlightLabel: number_format($publishedPosts).' đã xuất bản',
                highlightTone: 'info',
            ),
            $this->makeRow(
                label: 'Dịch vụ',
                group: 'Nội dung',
                url: ServiceResource::getUrl('index'),
                stats: $serviceStats,
                highlightLabel: number_format($activeServices).' đang hiển thị',
                highlightTone: 'info',
            ),
            $this->makeRow(
                label: 'Dự án',
                group: 'Nội dung',
                url: ProjectResource::getUrl('index'),
                stats: $projectStats,
                highlightLabel: number_format($activeProjects).' đang hiển thị',
                highlightTone: 'info',
            ),
            $this->makeRow(
                label: 'Tài liệu',
                group: 'Nội dung',
                url: DocumentResource::getUrl('index'),
                stats: $documentStats,
                highlightLabel: number_format($activeDocuments).' đang hiển thị',
                highlightTone: 'info',
            ),
        ];
    }

    /**
     * @param  Builder<*>  $query
     * @param  array<string, string>  $ranges
     * @return array{today: int, week: int, month: int, year: int, total: int}
     */
    private function summarizePeriods(Builder $query, array $ranges): array
    {
        $row = $query
            ->selectRaw(
                'COUNT(*) as total_count, '
                .'SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as today_count, '
                .'SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as week_count, '
                .'SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as month_count, '
                .'SUM(CASE WHEN created_at BETWEEN ? AND ? THEN 1 ELSE 0 END) as year_count',
                [
                    $ranges['today_start'],
                    $ranges['today_end'],
                    $ranges['week_start'],
                    $ranges['week_end'],
                    $ranges['month_start'],
                    $ranges['month_end'],
                    $ranges['year_start'],
                    $ranges['year_end'],
                ],
            )
            ->first();

        return [
            'today' => (int) ($row?->today_count ?? 0),
            'week' => (int) ($row?->week_count ?? 0),
            'month' => (int) ($row?->month_count ?? 0),
            'year' => (int) ($row?->year_count ?? 0),
            'total' => (int) ($row?->total_count ?? 0),
        ];
    }

    /**
     * @param  array{today: int, week: int, month: int, year: int, total: int}  $stats
     * @return array{
     *     label: string,
     *     group: string,
     *     url: string,
     *     today: int,
     *     week: int,
     *     month: int,
     *     year: int,
     *     total: int,
     *     highlight_label: string,
     *     highlight_tone: string
     * }
     */
    private function makeRow(
        string $label,
        string $group,
        string $url,
        array $stats,
        string $highlightLabel,
        string $highlightTone,
    ): array {
        return [
            'label' => $label,
            'group' => $group,
            'url' => $url,
            'today' => $stats['today'],
            'week' => $stats['week'],
            'month' => $stats['month'],
            'year' => $stats['year'],
            'total' => $stats['total'],
            'highlight_label' => $highlightLabel,
            'highlight_tone' => $highlightTone,
        ];
    }
}

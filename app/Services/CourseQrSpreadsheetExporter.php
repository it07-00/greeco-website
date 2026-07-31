<?php

namespace App\Services;

use App\Models\Course;
use Illuminate\Database\Eloquent\Collection;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Worksheet\MemoryDrawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class CourseQrSpreadsheetExporter
{
    public function __construct(
        private readonly CourseRegistrationQrCode $qrCode,
    ) {}

    public function download(): StreamedResponse
    {
        $filename = 'danh-sach-qr-khoa-hoc-'.now()->format('Y-m-d-His').'.xlsx';

        return response()->streamDownload(
            fn () => $this->writeTo('php://output'),
            $filename,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Cache-Control' => 'no-store, no-cache',
            ],
        );
    }

    public function writeTo(string $path): void
    {
        $courses = Course::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('title')
            ->get();

        $spreadsheet = new Spreadsheet;
        $imageResources = [];

        try {
            $this->buildSpreadsheet($spreadsheet, $courses, $imageResources);
            (new Xlsx($spreadsheet))->save($path);
        } finally {
            foreach ($imageResources as $imageResource) {
                imagedestroy($imageResource);
            }

            $spreadsheet->disconnectWorksheets();
        }
    }

    /**
     * @param  Collection<int, Course>  $courses
     * @param  array<int, \GdImage>  $imageResources
     */
    private function buildSpreadsheet(
        Spreadsheet $spreadsheet,
        Collection $courses,
        array &$imageResources,
    ): void {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Danh sách QR');
        $sheet->setShowGridlines(false);

        $spreadsheet->getProperties()
            ->setCreator('GREECO')
            ->setTitle('Danh sách mã QR đăng ký khóa học')
            ->setSubject('Link khóa học và mã QR đăng ký')
            ->setDescription('Danh sách các khóa học đang hiển thị trên website GREECO.');

        $spreadsheet->getDefaultStyle()
            ->getFont()
            ->setName('Arial')
            ->setSize(10)
            ->setColor(new Color('FF111111'));

        $sheet->mergeCells('A1:G1');
        $sheet->setCellValue('A1', 'DANH SÁCH MÃ QR ĐĂNG KÝ KHÓA HỌC');
        $sheet->getStyle('A1:G1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FF063F35'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(1)->setRowHeight(28);

        $sheet->mergeCells('A2:G2');
        $sheet->setCellValue(
            'A2',
            'Mỗi mã QR dẫn trực tiếp đến form đăng ký của khóa học và tự ghi nhận nguồn QR.',
        );
        $sheet->getStyle('A2:G2')->applyFromArray([
            'font' => [
                'italic' => true,
                'color' => ['argb' => 'FF111111'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FFEAF5F1'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $sheet->getRowDimension(2)->setRowHeight(24);

        $sheet->setCellValue('A4', 'Số khóa học');
        $sheet->setCellValue('B4', $courses->count());
        $sheet->setCellValue('D4', 'Ngày xuất');
        $sheet->setCellValue('E4', now()->format('d/m/Y H:i'));
        $sheet->getStyle('A4:G4')->getFont()->setBold(true);
        $sheet->getStyle('A4:G4')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
        $sheet->getRowDimension(4)->setRowHeight(22);

        $headers = [
            'STT',
            'Tên khóa học',
            'Học phí',
            'Link khóa học',
            'Link đăng ký',
            'Link ảnh QR',
            'Mã QR',
        ];
        $sheet->fromArray($headers, null, 'A6');
        $sheet->getStyle('A6:G6')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FF008F79'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFB7CBC5'],
                ],
            ],
        ]);
        $sheet->getRowDimension(6)->setRowHeight(30);

        foreach ($courses as $index => $course) {
            $row = $index + 7;
            $courseUrl = route('slug.show', ['slug' => $course->slug]);
            $registrationUrl = $this->qrCode->registrationUrl($course);
            $qrImageUrl = route('course-registration.qr', [
                'course' => $course,
                'format' => 'png',
            ]);

            $sheet->fromArray([
                $index + 1,
                $course->title,
                $course->price ?: 'Liên hệ',
                $courseUrl,
                $registrationUrl,
                $qrImageUrl,
                null,
            ], null, "A{$row}");

            foreach (['D' => $courseUrl, 'E' => $registrationUrl, 'F' => $qrImageUrl] as $column => $url) {
                $sheet->getCell("{$column}{$row}")->getHyperlink()->setUrl($url);
                $sheet->getStyle("{$column}{$row}")
                    ->getFont()
                    ->setColor(new Color('FF111111'))
                    ->setUnderline(true);
            }

            $png = $this->qrCode->render($course, 'png')->getString();
            $imageResource = imagecreatefromstring($png);

            if ($imageResource === false) {
                throw new RuntimeException("Không thể tạo ảnh QR cho khóa học {$course->id}.");
            }

            $imageResources[] = $imageResource;

            $drawing = new MemoryDrawing;
            $drawing->setName('QR đăng ký '.$course->title);
            $drawing->setDescription($registrationUrl);
            $drawing->setImageResource($imageResource);
            $drawing->setRenderingFunction(MemoryDrawing::RENDERING_PNG);
            $drawing->setMimeType(MemoryDrawing::MIMETYPE_PNG);
            $drawing->setCoordinates("G{$row}");
            $drawing->setOffsetX(11);
            $drawing->setOffsetY(7);
            $drawing->setHeight(82);
            $drawing->setWorksheet($sheet);

            $sheet->getRowDimension($row)->setRowHeight(72);
            $sheet->getStyle("A{$row}:G{$row}")->applyFromArray([
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'wrapText' => true,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FFD9E4E0'],
                    ],
                ],
            ]);
            $sheet->getStyle("A{$row}:C{$row}")
                ->getAlignment()
                ->setHorizontal(Alignment::HORIZONTAL_CENTER);

            if ($index % 2 === 1) {
                $sheet->getStyle("A{$row}:G{$row}")
                    ->getFill()
                    ->setFillType('solid')
                    ->getStartColor()
                    ->setARGB('FFF6FAF8');
            }
        }

        $lastRow = max(6, $courses->count() + 6);
        $sheet->setAutoFilter("A6:G{$lastRow}");
        $sheet->freezePane('A7');

        foreach ([
            'A' => 14,
            'B' => 42,
            'C' => 14,
            'D' => 48,
            'E' => 54,
            'F' => 54,
            'G' => 16,
        ] as $column => $width) {
            $sheet->getColumnDimension($column)->setWidth($width);
        }

        $sheet->getPageSetup()
            ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(PageSetup::PAPERSIZE_A4)
            ->setFitToWidth(1)
            ->setFitToHeight(0);
        $sheet->getPageMargins()
            ->setTop(0.4)
            ->setRight(0.3)
            ->setBottom(0.4)
            ->setLeft(0.3);
        $sheet->getPageSetup()->setRowsToRepeatAtTopByStartAndEnd(1, 6);
        $sheet->getHeaderFooter()
            ->setOddFooter('&LGREECO&CTrang &P / &N&R'.now()->format('d/m/Y'));

        $guide = $spreadsheet->createSheet();
        $guide->setTitle('Hướng dẫn');
        $guide->setShowGridlines(false);
        $guide->mergeCells('A1:D1');
        $guide->setCellValue('A1', 'HƯỚNG DẪN SỬ DỤNG');
        $guide->getStyle('A1:D1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 15,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FF063F35'],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
        ]);
        $guide->getRowDimension(1)->setRowHeight(28);
        $guide->fromArray([
            ['Nội dung', 'Hướng dẫn'],
            ['Mã QR', 'Quét mã để mở trực tiếp form đăng ký đúng khóa học.'],
            ['Link đăng ký', 'Đường dẫn có tham số source=qr để hệ thống ghi nhận nguồn đăng ký.'],
            ['Link ảnh QR', 'Mở hoặc tải ảnh QR PNG trực tiếp từ server.'],
            ['In ấn', 'Nên giữ mã QR tối thiểu 3 × 3 cm và không cắt phần viền trắng.'],
            ['Phạm vi dữ liệu', 'File chỉ gồm các khóa học đang bật trạng thái hiển thị.'],
        ], null, 'A3');
        $guide->getStyle('A3:B3')->applyFromArray([
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => 'solid',
                'startColor' => ['argb' => 'FF008F79'],
            ],
        ]);
        $guide->getStyle('A3:B8')->applyFromArray([
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FFD9E4E0'],
                ],
            ],
        ]);
        $guide->getStyle('A4:A8')->getFont()->setBold(true);
        $guide->getColumnDimension('A')->setWidth(24);
        $guide->getColumnDimension('B')->setWidth(76);

        for ($row = 3; $row <= 8; $row++) {
            $guide->getRowDimension($row)->setRowHeight($row === 3 ? 26 : 38);
        }

        $spreadsheet->setActiveSheetIndex(0);
    }
}

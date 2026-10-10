<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Services\CourseQrSpreadsheetExporter;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class CourseQrSpreadsheetExporterTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_text_is_not_interpreted_as_a_spreadsheet_formula(): void
    {
        Course::query()->delete();
        Course::create(['title' => '=1+1', 'slug' => 'formula-course', 'price' => '=2+2', 'is_active' => true]);
        $path = storage_path('framework/testing/course-qr-text.xlsx');
        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        try {
            app(CourseQrSpreadsheetExporter::class)->writeTo($path);
            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Danh sách QR');
            $this->assertSame('s', $sheet->getCell('B7')->getDataType());
            $this->assertSame('=1+1', $sheet->getCell('B7')->getValue());
            $this->assertSame('s', $sheet->getCell('C7')->getDataType());
            $this->assertSame('=2+2', $sheet->getCell('C7')->getValue());
            $spreadsheet->disconnectWorksheets();
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }

    public function test_it_exports_active_courses_with_links_and_embedded_qr_codes(): void
    {
        Course::query()->delete();

        $activeCourse = Course::query()->create([
            'title' => 'Khóa học đang hiển thị',
            'slug' => 'khoa-hoc-dang-hien-thi',
            'price' => 'Liên hệ',
            'is_active' => true,
            'sort_order' => 10,
        ]);

        Course::query()->create([
            'title' => 'Khóa học đang ẩn',
            'slug' => 'khoa-hoc-dang-an',
            'price' => 'Liên hệ',
            'is_active' => false,
            'sort_order' => 20,
        ]);

        $path = storage_path('framework/testing/course-qr-export.xlsx');

        if (! is_dir(dirname($path))) {
            mkdir(dirname($path), 0777, true);
        }

        try {
            app(CourseQrSpreadsheetExporter::class)->writeTo($path);

            $this->assertFileExists($path);

            $spreadsheet = IOFactory::load($path);
            $sheet = $spreadsheet->getSheetByName('Danh sách QR');

            $this->assertNotNull($sheet);
            $this->assertSame(1, $sheet->getCell('B4')->getValue());
            $this->assertSame($activeCourse->title, $sheet->getCell('B7')->getValue());
            $this->assertStringEndsWith(
                '/'.$activeCourse->slug,
                $sheet->getCell('D7')->getValue(),
            );
            $this->assertStringContainsString(
                '/dang-ky-khoa-hoc/'.$activeCourse->slug.'?source=qr',
                $sheet->getCell('E7')->getValue(),
            );
            $this->assertStringEndsWith(
                '/dang-ky-khoa-hoc/'.$activeCourse->slug.'/qr.png',
                $sheet->getCell('F7')->getValue(),
            );
            $this->assertSame(
                $sheet->getCell('E7')->getValue(),
                $sheet->getCell('E7')->getHyperlink()->getUrl(),
            );
            $this->assertCount(1, $sheet->getDrawingCollection());
            $this->assertSame(
                'Hướng dẫn',
                $spreadsheet->getSheet(1)->getTitle(),
            );
            $this->assertStringNotContainsString(
                'Khóa học đang ẩn',
                implode(' ', array_merge(...$sheet->toArray(null, true, true, false))),
            );

            $spreadsheet->disconnectWorksheets();
        } finally {
            if (is_file($path)) {
                unlink($path);
            }
        }
    }
}

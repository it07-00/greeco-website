<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseImportMigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_course_import_migration_matches_training_workbook_structure(): void
    {
        $this->assertSame(6, CourseCategory::query()->count());
        $this->assertSame(36, Course::query()->count());
        $this->assertSame([
            'iso' => 5,
            'hse' => 8,
            'carbon' => 8,
            'esg' => 4,
            'chuoi-cung-ung-ben-vung-va-kinh-te-tuan-hoan' => 9,
            'cong-trinh-xanh-va-san-pham-ben-vung' => 2,
        ], CourseCategory::query()
            ->withCount('courses')
            ->orderBy('sort_order')
            ->pluck('courses_count', 'slug')
            ->all());

        $this->assertDatabaseHas((new CourseCategory)->getTable(), [
            'name' => 'Hệ thống quản lý ISO và cải tiến doanh nghiệp',
            'slug' => 'iso',
        ]);

        $course = Course::query()
            ->where('title', 'Phát triển năng lực cán bộ An toàn – Sức khỏe – Môi trường (HSE OFFICER)')
            ->firstOrFail();

        $this->assertSame('hse', $course->category->slug);
        $this->assertSame('NGÀY 01 – NỀN TẢNG QUẢN LÝ HSE VÀ ĐÁNH GIÁ RỦI RO', $course->curriculum[0]['title']);
        $this->assertContains(
            'Chuyên đề 1 – Tổng quan về HSE trong doanh nghiệp',
            array_column($course->curriculum[0]['lessons'], 'title')
        );

        $isoCourse = Course::query()
            ->where('title', 'Xây dựng Hệ thống Quản lý Chất lượng ISO 9001:2015')
            ->firstOrFail();

        $this->assertContains(
            ['title' => 'Thời gian dự kiến', 'duration' => '02 ngày'],
            $isoCourse->curriculum[0]['lessons']
        );

        $cascaleCourse = Course::query()
            ->where('title', 'XÂY DỰNG HỆ THỐNG QUẢN LÝ LAO ĐỘNG VÀ TRÁCH NHIỆM XÃ HỘI THEO YÊU CẦU CASCALE')
            ->firstOrFail();

        $this->assertSame('GIAI ĐOẠN 1 – KHỞI ĐỘNG DỰ ÁN VÀ ĐÀO TẠO NHẬN THỨC', $cascaleCourse->curriculum[0]['title']);
    }
}

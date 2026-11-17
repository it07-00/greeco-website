<?php

namespace Tests\Feature;

use App\Filament\Resources\Contacts\Pages\ListContacts;
use App\Filament\Resources\CourseRegistrations\Pages\ListCourseRegistrations;
use App\Filament\Widgets\DetailedActivityBreakdownWidget;
use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseCategory;
use App\Models\CourseRegistration;
use App\Models\User;
use App\Services\ActivityStatsExcelExport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Tests\TestCase;

class AdminDashboardExcelTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_dashboard_widgets_and_export_full_excel(): void
    {
        $admin = User::factory()->create();
        $category = CourseCategory::create(['name' => 'Đào tạo GHG', 'slug' => 'dao-tao-ghg', 'is_active' => true]);
        $course = Course::create([
            'course_category_id' => $category->id,
            'title' => 'Kiểm kê Khí nhà kính ISO 14064-1',
            'slug' => 'kiem-ke-ghg',
            'price' => '2.500.000đ',
            'is_active' => true,
        ]);
        $registration = CourseRegistration::create([
            'course_id' => $course->id,
            'code' => 'GR-2026-001',
            'company_name' => 'Công ty TNHH Xanh GREECO',
            'company_address' => 'TP. Hồ Chí Minh',
            'tax_code' => '0312345678',
            'contact_name' => 'Nguyễn Văn A',
            'contact_email' => 'a@greeco.test',
            'contact_phone' => '0912345678',
            'attendee_count' => 1,
            'invoice_email' => 'invoice@greeco.test',
            'source' => 'website',
            'status' => 'new',
            'consent_at' => now(),
        ]);
        $registration->attendees()->create([
            'full_name' => 'Trần Thị B',
            'job_title' => 'Chuyên viên HSE',
            'email' => 'b@greeco.test',
            'phone' => '0987654321',
        ]);
        Contact::create([
            'name' => 'Lê Văn C',
            'email' => 'c@greeco.test',
            'phone' => '0909111222',
            'subject' => 'Tư vấn đào tạo',
            'message' => 'Xin báo giá khóa học',
        ]);

        $this->actingAs($admin);

        $this->get('/admin')
            ->assertOk()
            ->assertSee('Đăng ký khóa học mới')
            ->assertSee('Liên hệ chưa đọc')
            ->assertSee('Thống kê chi tiết hoạt động theo Ngày / Tuần / Tháng / Năm')
            ->assertSee('Xuất Excel (.xlsx)');

        $response = app(ActivityStatsExcelExport::class)->download();
        ob_start();
        $response->sendContent();
        $binary = ob_get_clean();

        $this->assertStringStartsWith("PK\x03\x04", $binary);

        $temp = tempnam(sys_get_temp_dir(), 'greeco-xlsx-test-');
        file_put_contents($temp, $binary);
        try {
            $spreadsheet = IOFactory::load($temp);
            $this->assertSame([
                'Tổng hợp hoạt động',
                'Đăng ký khóa học',
                'Học viên khóa học',
                'EduQuiz - Kết quả thi',
                'Nội dung & Đào tạo',
                'Liên hệ khách hàng',
            ], $spreadsheet->getSheetNames());

            $regSheet = $spreadsheet->getSheetByName('Đăng ký khóa học');
            $this->assertSame('GR-2026-001', $regSheet->getCell('A2')->getValue());
            $this->assertSame('Công ty TNHH Xanh GREECO', $regSheet->getCell('C2')->getValue());

            $attendeeSheet = $spreadsheet->getSheetByName('Học viên khóa học');
            $this->assertSame('Trần Thị B', $attendeeSheet->getCell('D2')->getValue());

            $contactSheet = $spreadsheet->getSheetByName('Liên hệ khách hàng');
            $this->assertSame('Lê Văn C', $contactSheet->getCell('A2')->getValue());
        } finally {
            @unlink($temp);
        }

        Livewire::test(DetailedActivityBreakdownWidget::class)
            ->call('exportExcel')
            ->assertFileDownloaded();

        Livewire::test(ListCourseRegistrations::class)
            ->callAction('export_excel')
            ->assertFileDownloaded();

        Livewire::test(ListContacts::class)
            ->callAction('export_excel')
            ->assertFileDownloaded();
    }

    public function test_instructor_cannot_export_admin_excel(): void
    {
        $instructor = User::factory()->create();
        DB::table('eduquiz_instructors')->insert(['user_id' => $instructor->id]);

        $this->actingAs($instructor);
        $this->expectException(\Symfony\Component\HttpKernel\Exception\HttpException::class);
        app(ActivityStatsExcelExport::class)->download();
    }
}

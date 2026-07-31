<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Services\CourseRegistrationQrCode;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CourseRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_active_course_registration_form_is_available(): void
    {
        $course = $this->createCourse();

        $response = $this->get(route('course-registration.create', $course));

        $response
            ->assertOk()
            ->assertSee('Thông tin đăng ký')
            ->assertSee($course->title)
            ->assertSee('Tên công ty')
            ->assertSee('Email nhận hóa đơn');
    }

    public function test_inactive_course_cannot_receive_registrations(): void
    {
        $course = $this->createCourse(['is_active' => false]);

        $this->get(route('course-registration.create', $course))->assertNotFound();
        $this->post(route('course-registration.store', $course), [])->assertNotFound();
    }

    public function test_registration_and_attendees_are_stored_in_database(): void
    {
        $course = $this->createCourse();

        $response = $this->post(route('course-registration.store', $course), [
            'company_name' => 'Công ty TNHH Xanh',
            'company_address' => '123 Đường Xanh, TP.HCM',
            'tax_code' => '0312345678',
            'attendee_count' => 2,
            'attendees' => [
                [
                    'full_name' => 'Nguyễn Văn A',
                    'job_title' => 'Trưởng phòng HSE',
                    'email' => 'a@example.com',
                    'phone' => '0901000001',
                ],
                [
                    'full_name' => 'Trần Thị B',
                    'job_title' => 'Chuyên viên',
                    'email' => 'b@example.com',
                    'phone' => '0901000002',
                ],
            ],
            'invoice_email' => 'invoice@example.com',
            'consultation_question' => 'Khóa học có bài tập thực hành không?',
            'source' => 'qr',
            'consent' => '1',
        ]);

        $registration = CourseRegistration::query()->sole();

        $response->assertRedirect(route('course-registration.success', $registration));
        $this->assertSame($course->id, $registration->course_id);
        $this->assertSame('Công ty TNHH Xanh', $registration->company_name);
        $this->assertSame('Nguyễn Văn A', $registration->contact_name);
        $this->assertSame('a@example.com', $registration->contact_email);
        $this->assertSame('0901000001', $registration->contact_phone);
        $this->assertSame(2, $registration->attendee_count);
        $this->assertSame('qr', $registration->source);
        $this->assertSame('new', $registration->status);
        $this->assertNotNull($registration->consent_at);
        $this->assertCount(2, $registration->attendees);
        $this->assertDatabaseHas('registration_attendees', [
            'course_registration_id' => $registration->id,
            'full_name' => 'Trần Thị B',
            'email' => 'b@example.com',
        ]);

        $this->get(route('course-registration.success', $registration))
            ->assertOk()
            ->assertSee($registration->code)
            ->assertSee($course->title);
    }

    public function test_attendee_count_must_match_submitted_attendees(): void
    {
        $course = $this->createCourse();

        $response = $this->from(route('course-registration.create', $course))
            ->post(route('course-registration.store', $course), [
                'company_name' => 'Công ty TNHH Xanh',
                'company_address' => '123 Đường Xanh, TP.HCM',
                'tax_code' => '0312345678',
                'attendee_count' => 2,
                'attendees' => [
                    [
                        'full_name' => 'Nguyễn Văn A',
                        'job_title' => '',
                        'email' => 'a@example.com',
                        'phone' => '0901000001',
                    ],
                ],
                'invoice_email' => 'invoice@example.com',
                'source' => 'website',
                'consent' => '1',
            ]);

        $response
            ->assertRedirect(route('course-registration.create', $course))
            ->assertSessionHasErrors('attendees');

        $this->assertDatabaseCount('course_registrations', 0);
    }

    public function test_course_qr_can_be_rendered_and_downloaded(): void
    {
        $course = $this->createCourse();

        $this->get(route('course-registration.qr', [
            'course' => $course,
            'format' => 'png',
        ]))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        $this->get(route('course-registration.qr', [
            'course' => $course,
            'format' => 'svg',
            'download' => 1,
        ]))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml')
            ->assertHeader(
                'Content-Disposition',
                'attachment; filename="qr-dang-ky-khoa-hoc-kiem-thu.svg"'
            );
    }

    public function test_shared_registration_page_lists_active_courses_in_a_select(): void
    {
        $activeCourse = $this->createCourse([
            'title' => 'Khóa học dành cho QR chung',
            'slug' => 'khoa-hoc-danh-cho-qr-chung',
        ]);
        $inactiveCourse = $this->createCourse([
            'title' => 'Khóa học đang ẩn khỏi QR chung',
            'slug' => 'khoa-hoc-dang-an-khoi-qr-chung',
            'is_active' => false,
        ]);

        $this->get(route('course-registration.index'))
            ->assertOk()
            ->assertSee('name="course"', false)
            ->assertSee($activeCourse->title)
            ->assertSee('value="'.$activeCourse->slug.'"', false)
            ->assertDontSee($inactiveCourse->title);
    }

    public function test_shared_registration_page_redirects_to_the_selected_course_with_qr_source(): void
    {
        $course = $this->createCourse([
            'slug' => 'khoa-hoc-duoc-chon-tu-qr-chung',
        ]);

        $this->get(route('course-registration.index', [
            'course' => $course->slug,
        ]))->assertRedirect(route('course-registration.create', [
            'course' => $course,
            'source' => 'qr',
        ]));
    }

    public function test_shared_qr_points_to_the_course_selection_page(): void
    {
        $qrCode = app(CourseRegistrationQrCode::class);

        $this->assertStringEndsWith('/dang-ky-khoa-hoc/', $qrCode->selectionUrl());

        $this->get(route('course-registration.selection-qr', [
            'format' => 'png',
        ]))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/png');

        $this->get(route('course-registration.selection-qr', [
            'format' => 'svg',
            'download' => 1,
        ]))
            ->assertOk()
            ->assertHeader('Content-Type', 'image/svg+xml')
            ->assertHeader(
                'Content-Disposition',
                'attachment; filename="qr-chung-dang-ky-khoa-hoc.svg"'
            );
    }

    private function createCourse(array $attributes = []): Course
    {
        return Course::query()->create(array_merge([
            'title' => 'Khóa học kiểm thử',
            'slug' => 'khoa-hoc-kiem-thu',
            'description' => 'Mô tả khóa học kiểm thử.',
            'price' => 'Liên hệ',
            'is_active' => true,
            'sort_order' => 0,
        ], $attributes));
    }
}

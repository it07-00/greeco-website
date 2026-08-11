<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WorkshopRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_all_six_workshops_are_listed_on_course_registration_page(): void
    {
        $category = CourseCategory::query()->where('slug', 'workshop')->first();
        $this->assertNotNull($category, 'Workshop category should exist in database.');
        $this->assertSame(6, $category->courses()->count());

        $response = $this->get(route('course-registration.index'));

        $response->assertOk();
        $response->assertSee('<optgroup label="Workshop">', false);

        $expectedWorkshops = [
            'Workshop: Open-Source AI Agent Lab',
            'Workshop: Data Engineering Lab',
            'Workshop: Computer Vision Lab',
            'Workshop: Edge AI Lab',
            'Workshop: AI Deployment Lab',
            'Workshop: Robotics Lab - series finale',
        ];

        foreach ($expectedWorkshops as $title) {
            $response->assertSee($title);
        }
    }

    public function test_selecting_a_workshop_redirects_to_registration_create_form(): void
    {
        $workshop = Course::query()->where('slug', 'workshop-open-source-ai-agent-lab')->firstOrFail();

        $response = $this->get(route('course-registration.index', ['course' => $workshop->slug]));

        $response->assertRedirect(route('course-registration.create', [
            'course' => $workshop,
            'source' => 'qr',
        ]));

        $this->get(route('course-registration.create', $workshop))
            ->assertOk()
            ->assertSee($workshop->title)
            ->assertSee('Thông tin đăng ký');
    }
}

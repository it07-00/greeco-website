<?php

namespace Tests\Feature;

use App\Models\Contact;
use Database\Seeders\CourseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TrainingAcademyPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_training_page_promotes_courses_without_lms_features(): void
    {
        $this->seed(CourseSeeder::class);

        $this->get(route('services.dao-tao'))
            ->assertOk()
            ->assertSee('class="academy-catalog-page"', false)
            ->assertSee('Nâng cao năng lực Phát triển bền vững')
            ->assertSee('id="chuong-trinh-dao-tao"', false)
            ->assertSee('Khóa học nổi bật')
            ->assertSee('ISO 9001:2015')
            ->assertSee('class="btn btn-sm btn-register-now"', false)
            ->assertSee('Xem chi tiết')
            ->assertDontSee('Tiến độ học tập')
            ->assertSee('<header class="transparent scroll-light">', false)
            ->assertSee('<footer class="footer-light">', false)
            ->assertSee('assets/css/custom.css', false);
    }

    public function test_training_inquiry_returns_to_the_inline_form(): void
    {
        $response = $this->from(route('services.dao-tao'))
            ->post(route('contact.store'), [
                '_return' => 'training',
                'name' => 'Nguyễn Văn A',
                'email' => 'a@example.com',
                'phone' => '0900000000',
                'subject' => 'Hệ thống quản lý ISO',
                'message' => 'Cần tư vấn chương trình đào tạo in-house.',
            ]);

        $response->assertRedirect(route('services.dao-tao'));

        $this->assertDatabaseHas((new Contact())->getTable(), [
            'name' => 'Nguyễn Văn A',
            'subject' => 'Hệ thống quản lý ISO',
        ]);
    }

    public function test_old_training_urls_redirect_to_new_ones(): void
    {
        $this->seed(CourseSeeder::class);

        // Test catalog redirect
        $this->get('/dich-vu/dao-tao-va-boi-duong')
            ->assertRedirect(route('services.dao-tao'));

        // Test detail page redirect
        $this->get('/dich-vu/dao-tao-va-boi-duong/iso-9001-2015-xay-dung-he-thong-quan-ly-chat-luong-hieu-qua')
            ->assertRedirect(route('services.course-detail', ['slug' => 'iso-9001-2015-xay-dung-he-thong-quan-ly-chat-luong-hieu-qua']));
    }
}

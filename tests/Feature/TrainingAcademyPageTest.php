<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\Course;
use App\Models\CourseCategory;
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
            ->assertSee('id="course-search-suggestions"', false)
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

        $this->assertDatabaseHas((new Contact)->getTable(), [
            'name' => 'Nguyễn Văn A',
            'subject' => 'Hệ thống quản lý ISO',
        ]);
    }

    public function test_training_page_paginates_courses_nine_per_page(): void
    {
        foreach (range(1, 10) as $index) {
            Course::query()->create([
                'title' => sprintf('Pagination Course %02d', $index),
                'slug' => sprintf('pagination-course-%02d', $index),
                'description' => 'Course used to verify academy catalog pagination.',
                'price' => 'Lien he',
                'is_active' => true,
                'sort_order' => -100 + $index,
            ]);
        }

        $response = $this->get(route('services.dao-tao'));

        $response
            ->assertOk()
            ->assertSee('Pagination Course 01')
            ->assertSee('Pagination Course 09')
            ->assertDontSee('Pagination Course 10')
            ->assertSee('page=2', false)
            ->assertSee('aria-label="Phân trang khóa học"', false)
            ->assertDontSee('pagination.next', false)
            ->assertDontSee('Showing 1 to', false)
            ->assertDontSee('w-5 h-5', false);

        $this->assertSame(9, substr_count($response->getContent(), 'class="col course-catalog-item"'));

        $this->get(route('services.dao-tao', ['page' => 2]))
            ->assertOk()
            ->assertSee('Pagination Course 10')
            ->assertDontSee('Pagination Course 01');
    }

    public function test_training_page_searches_active_courses_in_the_database(): void
    {
        foreach (range(1, 10) as $index) {
            Course::query()->create([
                'title' => sprintf('Filler Course %02d', $index),
                'slug' => sprintf('filler-course-%02d', $index),
                'description' => 'Course used to fill the first catalog page.',
                'price' => 'Lien he',
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }

        Course::query()->create([
            'title' => 'Net Zero Database Match',
            'slug' => 'net-zero-database-match',
            'description' => 'This course only exists beyond the first page.',
            'price' => 'Lien he',
            'is_active' => true,
            'sort_order' => 100,
        ]);

        $this->get(route('services.dao-tao', ['search' => 'Net Zero']))
            ->assertOk()
            ->assertSee('Net Zero Database Match')
            ->assertDontSee('Filler Course 01')
            ->assertSee('1 khóa học phù hợp');

        $this->getJson(route('services.dao-tao', ['search' => 'Net Zero']))
            ->assertOk()
            ->assertJsonPath('count', 1)
            ->assertJsonPath('search', 'Net Zero')
            ->assertJsonFragment(['category' => ''])
            ->assertJsonPath('html', fn (string $html) => str_contains($html, 'Net Zero Database Match'))
            ->assertJsonPath('suggestions_html', fn (string $html) => str_contains($html, 'Net Zero Database Match')
                && str_contains($html, 'course-search-suggestion'));
    }

    public function test_training_page_filters_courses_by_category_slug_across_all_pages(): void
    {
        $iso = CourseCategory::query()->create([
            'name' => 'Filter ISO',
            'slug' => 'filter-iso',
            'sort_order' => 1,
        ]);
        $esg = CourseCategory::query()->create([
            'name' => 'Filter ESG',
            'slug' => 'filter-esg',
            'sort_order' => 2,
        ]);

        foreach (range(1, 9) as $index) {
            Course::query()->create([
                'course_category_id' => $iso->id,
                'title' => sprintf('ISO Course %02d', $index),
                'slug' => sprintf('iso-course-%02d', $index),
                'price' => 'Lien he',
                'is_active' => true,
                'sort_order' => $index,
            ]);
        }

        Course::query()->create([
            'course_category_id' => $esg->id,
            'title' => 'ESG Course Beyond Page One',
            'slug' => 'esg-course-beyond-page-one',
            'price' => 'Lien he',
            'is_active' => true,
            'sort_order' => 100,
        ]);

        $this->get(route('services.dao-tao', ['category' => 'filter-esg']))
            ->assertOk()
            ->assertSee('ESG Course Beyond Page One')
            ->assertDontSee('ISO Course 01')
            ->assertSee('class="category-box h-100 active"', false)
            ->assertSee('aria-pressed="true"', false);
    }

    public function test_course_categories_are_presented_before_live_search(): void
    {
        $this->seed(CourseSeeder::class);

        $this->get(route('services.dao-tao'))
            ->assertOk()
            ->assertSeeInOrder([
                'Danh mục khóa học',
                'id="search-courses-form"',
                'id="chuong-trinh-dao-tao"',
            ], false);
    }

    public function test_old_training_urls_redirect_to_new_ones(): void
    {
        $this->seed(CourseSeeder::class);

        // Test catalog redirect
        $this->get('/dich-vu/dao-tao-va-boi-duong')
            ->assertRedirect(route('services.dao-tao'));

        // Test detail page redirect
        $this->get('/dich-vu/dao-tao-va-boi-duong/iso-9001-2015-xay-dung-he-thong-quan-ly-chat-luong-hieu-qua')
            ->assertRedirect(route('slug.show', ['slug' => 'iso-9001-2015-xay-dung-he-thong-quan-ly-chat-luong-hieu-qua']));
    }
}

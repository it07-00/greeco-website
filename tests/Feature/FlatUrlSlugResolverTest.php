<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FlatUrlSlugResolverTest extends TestCase
{
    use RefreshDatabase;

    public function test_old_detail_urls_return_301_redirect_to_flat_slug_url(): void
    {
        $post = Post::query()->create([
            'title' => 'Bài viết tin tức',
            'slug' => 'bai-viet-tin-tuc-test',
            'excerpt' => 'Tóm tắt bài viết.',
            'content' => '<p>Nội dung.</p>',
            'is_published' => true,
        ]);

        $project = Project::query()->create([
            'title' => 'Dự án mẫu',
            'slug' => 'du-an-mau-test',
            'excerpt' => 'Mô tả dự án.',
            'is_active' => true,
        ]);

        $service = Service::query()->create([
            'name' => 'Dịch vụ tư vấn',
            'slug' => 'dich-vu-tu-van-test',
            'short_description' => 'Mô tả dịch vụ.',
            'is_active' => true,
        ]);

        $this->get('/tin-tuc/' . $post->slug)
            ->assertStatus(301)
            ->assertRedirect('/' . $post->slug);

        $this->get('/dao-tao-va-boi-duong/khoa-hoc-test')
            ->assertStatus(301)
            ->assertRedirect('/khoa-hoc-test');

        $this->get('/du-an/' . $project->slug)
            ->assertStatus(301)
            ->assertRedirect('/' . $project->slug);

        $this->get('/dich-vu/' . $service->slug)
            ->assertStatus(301)
            ->assertRedirect('/' . $service->slug);
    }

    public function test_flat_slug_resolves_all_content_types(): void
    {
        $post = Post::query()->create([
            'title' => 'Bài viết tin tức',
            'slug' => 'bai-viet-tin-tuc-resolution',
            'excerpt' => 'Tóm tắt bài viết.',
            'content' => '<p>Nội dung bài viết.</p>',
            'is_published' => true,
        ]);

        $course = Course::query()->create([
            'title' => 'Khóa học ISO',
            'slug' => 'khoa-hoc-iso-resolution',
            'description' => 'Mô tả khóa học.',
            'price' => 'Liên hệ',
            'is_active' => true,
        ]);

        $project = Project::query()->create([
            'title' => 'Dự án năng lượng',
            'slug' => 'du-an-nang-luong-resolution',
            'excerpt' => 'Mô tả dự án.',
            'is_active' => true,
        ]);

        $service = Service::query()->create([
            'name' => 'Tư vấn Net Zero',
            'slug' => 'tu-van-net-zero-resolution',
            'short_description' => 'Tư vấn chuyển đổi.',
            'is_active' => true,
        ]);

        $page = Page::query()->create([
            'title' => 'Điều khoản sử dụng',
            'slug' => 'dieu-khoan-su-dung-resolution',
            'content' => '<p>Điều khoản.</p>',
            'is_active' => true,
        ]);

        $this->get('/' . $post->slug)->assertStatus(200)->assertSee('Bài viết tin tức');
        $this->get('/' . $course->slug)->assertStatus(200)->assertSee('Khóa học ISO');
        $this->get('/' . $project->slug)->assertStatus(200)->assertSee('Dự án năng lượng');
        $this->get('/' . $service->slug)->assertStatus(200)->assertSee('Tư vấn Net Zero');
        $this->get('/' . $page->slug)->assertStatus(200)->assertSee('Điều khoản sử dụng');
    }
}

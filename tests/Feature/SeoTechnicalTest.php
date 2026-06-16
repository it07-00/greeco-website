<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoTechnicalTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_static_routes_render_core_seo_tags_and_one_h1(): void
    {
        Page::query()->create([
            'title' => 'Giới thiệu về Viện GREECO',
            'slug' => 'gioi-thieu',
            'excerpt' => 'Trang giới thiệu phục vụ kiểm tra SEO.',
            'content' => '<p>Nội dung giới thiệu.</p>',
            'is_active' => true,
            'is_indexable' => true,
        ]);

        $routes = [
            'home',
            'about',
            'team',
            'gallery',
            'capability-profile',
            'library',
            'price-list',
            'pricing-plans',
            'services.index',
            'services.dao-tao',
            'services.tu-van',
            'services.du-an',
            'services.nghien-cuu',
            'services.hoi-thao',
            'projects.index',
            'posts.index',
            'contact',
        ];

        foreach ($routes as $routeName) {
            $content = $this->get(route($routeName))
                ->assertOk()
                ->getContent();

            $this->assertMatchesRegularExpression('/<title>[^<]+<\/title>/', $content, $routeName);
            $this->assertMatchesRegularExpression('/<meta name="description" content="[^"]+">/', $content, $routeName);
            $this->assertStringContainsString('<link rel="canonical" href="'.route($routeName).'">', $content, $routeName);
            $this->assertStringContainsString('<meta name="robots" content="index, follow, max-snippet:-1, max-image-preview:large, max-video-preview:-1">', $content, $routeName);
            $this->assertSame(1, preg_match_all('/<h1\b/i', $content), $routeName);
        }
    }

    public function test_sitemap_covers_public_static_cms_and_course_urls_without_duplicates(): void
    {
        Page::query()->create([
            'title' => 'Tầm nhìn thử nghiệm',
            'slug' => 'tam-nhin-thu-nghiem',
            'excerpt' => 'Trang tĩnh indexable.',
            'content' => '<p>Nội dung trang.</p>',
            'is_active' => true,
            'is_indexable' => true,
        ]);

        Page::query()->create([
            'title' => 'Giới thiệu',
            'slug' => 'gioi-thieu',
            'excerpt' => 'Trang tĩnh đã có route riêng.',
            'content' => '<p>Nội dung trang.</p>',
            'is_active' => true,
            'is_indexable' => true,
        ]);

        Page::query()->create([
            'title' => 'Không index',
            'slug' => 'khong-index',
            'excerpt' => 'Không xuất hiện trong sitemap.',
            'content' => '<p>Nội dung trang.</p>',
            'is_active' => true,
            'is_indexable' => false,
        ]);

        $course = Course::query()->create([
            'title' => 'ISO 9001:2015 cho doanh nghiệp',
            'slug' => 'iso-9001-2015-cho-doanh-nghiep',
            'description' => 'Khóa đào tạo ISO 9001.',
            'price' => 'Liên hệ',
            'is_active' => true,
            'is_indexable' => true,
        ]);

        Course::query()->create([
            'title' => 'Khóa đào tạo nội bộ',
            'slug' => 'khoa-dao-tao-noi-bo',
            'description' => 'Không xuất hiện trong sitemap.',
            'price' => 'Liên hệ',
            'is_active' => true,
            'is_indexable' => false,
        ]);

        $content = $this->get(route('sitemap'))
            ->assertOk()
            ->assertHeader('Content-Type', 'text/xml; charset=UTF-8')
            ->getContent();

        $this->assertStringContainsString('<loc>'.route('team').'</loc>', $content);
        $this->assertStringContainsString('<loc>'.route('services.dao-tao').'</loc>', $content);
        $this->assertStringContainsString('<loc>'.route('services.course-detail', ['slug' => $course->slug]).'</loc>', $content);
        $this->assertStringContainsString('<loc>'.route('pages.show', ['page' => 'tam-nhin-thu-nghiem']).'</loc>', $content);
        $this->assertStringNotContainsString('khong-index', $content);
        $this->assertStringNotContainsString('khoa-dao-tao-noi-bo', $content);
        $this->assertSame(1, substr_count($content, '<loc>'.route('about').'</loc>'));
    }

    public function test_course_detail_uses_admin_seo_fields(): void
    {
        $course = Course::query()->create([
            'title' => 'ISO 14001 nội bộ',
            'slug' => 'iso-14001-noi-bo',
            'description' => 'Mô tả mặc định.',
            'meta_title' => 'Meta khóa ISO 14001',
            'meta_description' => 'Mô tả SEO từ admin cho khóa ISO 14001.',
            'canonical_url' => 'https://greeco.vn/khoa-hoc-iso-14001',
            'og_image' => 'seo/og-images/iso-14001.jpg',
            'price' => 'Liên hệ',
            'is_active' => true,
            'is_indexable' => false,
        ]);

        $this->get(route('services.course-detail', ['slug' => $course->slug]))
            ->assertOk()
            ->assertSee('<title>Meta khóa ISO 14001</title>', false)
            ->assertSee('<meta name="description" content="Mô tả SEO từ admin cho khóa ISO 14001.">', false)
            ->assertSee('<link rel="canonical" href="https://greeco.vn/khoa-hoc-iso-14001">', false)
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false)
            ->assertSee('<meta property="og:image" content="'.asset('storage/seo/og-images/iso-14001.jpg').'">', false);
    }

    public function test_cms_page_route_renders_canonical_and_noindex_when_configured(): void
    {
        $page = Page::query()->create([
            'title' => 'Chính sách thử nghiệm',
            'slug' => 'chinh-sach-thu-nghiem',
            'excerpt' => 'Trang pháp lý nội bộ.',
            'content' => '<p>Nội dung trang.</p>',
            'is_active' => true,
            'is_indexable' => false,
        ]);

        $this->get(route('pages.show', $page))
            ->assertOk()
            ->assertSee('<link rel="canonical" href="'.route('pages.show', $page).'">', false)
            ->assertSee('<meta name="robots" content="noindex, nofollow">', false);
    }

    public function test_article_json_ld_is_valid_when_content_contains_quotes(): void
    {
        $post = Post::query()->create([
            'title' => 'Net Zero "A" & ESG',
            'slug' => 'net-zero-a-esg',
            'excerpt' => 'Góc nhìn về "Net Zero" và ESG.',
            'content' => '<p>Nội dung bài viết.</p>',
            'is_published' => true,
            'is_indexable' => true,
            'published_at' => now(),
        ]);

        $content = $this->get(route('posts.show', $post))
            ->assertOk()
            ->getContent();

        preg_match_all('#<script type="application/ld\+json">\s*(.*?)\s*</script>#s', $content, $matches);

        $this->assertNotEmpty($matches[1]);

        $schemas = collect($matches[1])->map(function (string $json): array {
            $decoded = json_decode($json, true);

            $this->assertIsArray($decoded, json_last_error_msg());

            return $decoded;
        });

        $article = $schemas->firstWhere('@type', 'Article');

        $this->assertSame($post->title, $article['headline']);
    }

    public function test_post_detail_has_single_h1(): void
    {
        $post = Post::query()->create([
            'title' => 'Một tiêu đề bài viết chuẩn SEO',
            'slug' => 'mot-tieu-de-bai-viet-chuan-seo',
            'excerpt' => 'Tóm tắt bài viết.',
            'content' => '<p>Nội dung bài viết.</p>',
            'is_published' => true,
            'is_indexable' => true,
            'published_at' => now(),
        ]);

        $content = $this->get(route('posts.show', $post))
            ->assertOk()
            ->getContent();

        $this->assertSame(1, preg_match_all('/<h1\b/i', $content));
    }
}

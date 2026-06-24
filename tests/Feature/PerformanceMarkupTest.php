<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PerformanceMarkupTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_prioritizes_only_the_first_banner_image(): void
    {
        Banner::query()->create([
            'image' => 'banners/first.webp',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Banner::query()->create([
            'image' => 'banners/second.webp',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        $content = $this->get('/')
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression(
            '/<link[^>]+rel="preload"[^>]+as="image"[^>]+first\.webp[^>]+fetchpriority="high"/',
            $content,
        );
        $this->assertMatchesRegularExpression(
            '/<img[^>]+first\.webp[^>]+width="2160"[^>]+height="1215"[^>]+loading="eager"[^>]+fetchpriority="high"/',
            $content,
        );
        $this->assertMatchesRegularExpression(
            '/<img[^>]+second\.webp[^>]+width="2160"[^>]+height="1215"[^>]+loading="lazy"[^>]+fetchpriority="low"/',
            $content,
        );
    }

    public function test_home_defers_images_below_the_first_viewport(): void
    {
        $content = $this->get('/')
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression(
            '/<img[^>]+misc\/1\.webp[^>]+loading="lazy"[^>]+decoding="async"/',
            $content,
        );
        $this->assertMatchesRegularExpression(
            '/<img[^>]+background\/8\.webp[^>]+loading="lazy"[^>]+decoding="async"/',
            $content,
        );
    }

    public function test_layout_versions_assets_and_defers_scripts(): void
    {
        $content = $this->get('/')
            ->assertOk()
            ->getContent();

        $this->assertStringNotContainsString('fonts.googleapis.com', $content);
        $this->assertStringNotContainsString('fonts.gstatic.com', $content);
        $this->assertStringNotContainsString('id="de-loader"', $content);
        $this->assertMatchesRegularExpression(
            '/<link[^>]+rel="preload"[^>]+BeVietnamPro-Regular\.ttf[^>]+as="font"/',
            $content,
        );
        $this->assertStringNotContainsString('fa-solid-900.woff2', $content);
        $this->assertStringNotContainsString('fa-brands-400.woff2', $content);
        $this->assertStringNotContainsString('icofont.woff2', $content);
        $this->assertMatchesRegularExpression(
            '/<link[^>]+rel="preload"[^>]+coloring\.css[^>]+as="style"[^>]+onload=/',
            $content,
        );
        $this->assertStringContainsString('assets/css/style.css', $content);
        $this->assertMatchesRegularExpression('/assets\/js\/plugins\.js(?:\?v=\d+)?" defer/', $content);
        $this->assertMatchesRegularExpression('/assets\/js\/designesia\.js(?:\?v=\d+)?" defer/', $content);
    }

    public function test_page_scripts_wait_for_the_deferred_global_scripts(): void
    {
        Page::query()->create([
            'title' => 'Giới thiệu',
            'slug' => 'gioi-thieu',
            'content' => '<p>Giới thiệu GREECO</p>',
            'is_active' => true,
        ]);

        $this->get('/gioi-thieu')
            ->assertOk()
            ->assertSee("document.addEventListener('DOMContentLoaded'", false);

        $this->get('/ho-so-nang-luc')
            ->assertOk()
            ->assertSee('dflip.min.js" defer', false);
    }

    public function test_static_assets_are_configured_for_fast_repeat_visits(): void
    {
        $htaccess = file_get_contents(public_path('.htaccess'));
        $style = file_get_contents(public_path('assets/css/style.css'));
        $themeScript = file_get_contents(public_path('assets/js/designesia.js'));
        $fontAwesome = file_get_contents(public_path('assets/fonts/fontawesome4/css/font-awesome.css'));
        $elegantIcons = file_get_contents(public_path('assets/fonts/elegant_font/HTML_CSS/style.css'));
        $etLine = file_get_contents(public_path('assets/fonts/et-line-font/style.css'));
        $icoFont = file_get_contents(public_path('assets/fonts/icofont/icofont.min.css'));

        $this->assertStringContainsString('ExpiresActive On', $htaccess);
        $this->assertStringContainsString('Cache-Control', $htaccess);
        $this->assertStringContainsString('BROTLI_COMPRESS', $htaccess);
        $this->assertStringNotContainsString('@import url(', $style);
        $this->assertStringContainsString('@font-face', $style);
        $this->assertStringContainsString('BeVietnamPro-Regular.ttf', $style);
        $this->assertStringContainsString('font-display: swap', $style);
        $this->assertStringContainsString("\$('[data-bgimage]')", $themeScript);
        $this->assertStringNotContainsString('body,div,section").css(\'background-size\'', $themeScript);
        $this->assertStringContainsString('font-display: swap', $fontAwesome);
        $this->assertStringContainsString('font-display: swap', $elegantIcons);
        $this->assertStringContainsString('font-display: swap', $etLine);
        $this->assertStringContainsString('font-display:swap', $icoFont);
    }

    public function test_static_gallery_images_reserve_space_and_lazy_load(): void
    {
        \App\Models\Gallery::query()->create([
            'image' => 'gallery/1.webp',
            'title' => 'Hoạt động nghiên cứu thực địa',
            'is_active' => true,
        ]);

        \App\Models\Gallery::query()->create([
            'image' => 'gallery/9.webp',
            'title' => 'Thực nghiệm kỹ thuật hóa học',
            'is_active' => true,
        ]);

        $content = $this->get(route('gallery'))
            ->assertOk()
            ->getContent();

        $this->assertMatchesRegularExpression(
            '/<img[^>]+gallery\/1\.webp[^>]+width="1024"[^>]+height="1024"[^>]+loading="lazy"[^>]+decoding="async"/',
            $content,
        );
        $this->assertMatchesRegularExpression(
            '/<img[^>]+gallery\/9\.webp[^>]+width="1024"[^>]+height="1024"[^>]+loading="lazy"[^>]+decoding="async"/',
            $content,
        );
    }

    public function test_blog_cards_use_native_lazy_images_instead_of_js_backgrounds(): void
    {
        Post::query()->create([
            'title' => 'Bài viết tối ưu ảnh card',
            'slug' => 'bai-viet-toi-uu-anh-card',
            'excerpt' => 'Tóm tắt bài viết.',
            'content' => '<p>Nội dung bài viết.</p>',
            'is_published' => true,
            'is_indexable' => true,
            'published_at' => now(),
        ]);

        $postsIndex = $this->get(route('posts.index'))
            ->assertOk()
            ->getContent();

        $home = $this->get(route('home'))
            ->assertOk()
            ->getContent();

        foreach ([$postsIndex, $home] as $content) {
            $this->assertStringNotContainsString('data-bgimage', $content);
            $this->assertMatchesRegularExpression(
                '/<img(?=[^>]+class="blog-post-img")(?=[^>]+width="1024")(?=[^>]+height="1024")(?=[^>]+loading="lazy")(?=[^>]+decoding="async")[^>]*>/',
                $content,
            );
        }
    }
}

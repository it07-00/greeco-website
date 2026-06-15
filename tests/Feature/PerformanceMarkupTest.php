<?php

namespace Tests\Feature;

use App\Models\Banner;
use App\Models\Page;
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

        $this->assertStringContainsString('assets/css/style.css', $content);
        $this->assertMatchesRegularExpression('/assets\/js\/plugins\.js" defer/', $content);
        $this->assertMatchesRegularExpression('/assets\/js\/designesia\.js" defer/', $content);
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
        $fontAwesome = file_get_contents(public_path('assets/fonts/fontawesome4/css/font-awesome.css'));
        $elegantIcons = file_get_contents(public_path('assets/fonts/elegant_font/HTML_CSS/style.css'));
        $etLine = file_get_contents(public_path('assets/fonts/et-line-font/style.css'));
        $icoFont = file_get_contents(public_path('assets/fonts/icofont/icofont.min.css'));

        $this->assertStringContainsString('ExpiresActive On', $htaccess);
        $this->assertStringContainsString('Cache-Control', $htaccess);
        $this->assertStringContainsString('BROTLI_COMPRESS', $htaccess);
        $this->assertStringNotContainsString('@import url(', $style);
        $this->assertStringContainsString('font-display: swap', $fontAwesome);
        $this->assertStringContainsString('font-display: swap', $elegantIcons);
        $this->assertStringContainsString('font-display: swap', $etLine);
        $this->assertStringContainsString('font-display:swap', $icoFont);
    }
}

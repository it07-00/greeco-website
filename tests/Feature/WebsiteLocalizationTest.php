<?php

namespace Tests\Feature;

use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Page;
use App\Models\Service;
use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class WebsiteLocalizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_english_pages_translate_navigation_forms_metadata_and_switch_back_to_vietnamese(): void
    {
        $url = route('contact', ['lang' => 'en']);
        $this->get($url)->assertOk()->assertHeader('Content-Language', 'en')
            ->assertSee('<html lang="en">', false)
            ->assertSee('Send us a message')
            ->assertSee('value="Send message"', false)
            ->assertSee('href="'.route('home', ['lang' => 'en']).'"', false)
            ->assertSee('href="'.route('contact').'"', false)
            ->assertSee('<link rel="canonical" href="'.str_replace('http:', 'https:', $url).'">', false)
            ->assertSee('hreflang="en"', false)
            ->assertSee('content="en_US"', false);

        $this->get(route('contact'))->assertOk()->assertHeader('Content-Language', 'vi')
            ->assertSee('<html lang="vi">', false)->assertSee('Gửi tin nhắn cho chúng tôi');

        $this->get(route('contact', ['lang' => ['en']]))->assertOk()->assertHeader('Content-Language', 'vi');
        $this->get(route('contact', ['lang' => 'invalid']))->assertOk()->assertHeader('Content-Language', 'vi');
    }

    public function test_public_pages_render_in_both_languages(): void
    {
        Page::create(['title' => 'Giới thiệu', 'slug' => 'gioi-thieu', 'is_active' => true]);
        foreach (['vi', 'en'] as $locale) {
            foreach (['home', 'about', 'team', 'gallery', 'capability-profile', 'library', 'price-list', 'pricing-plans', 'services.index', 'services.tu-van', 'services.du-an', 'services.nghien-cuu', 'services.hoi-thao', 'services.dao-tao', 'projects.index', 'posts.index', 'course-registration.index'] as $name) {
                $this->get(route($name, ['lang' => $locale]))->assertOk()->assertHeader('Content-Language', $locale);
            }
        }
    }

    public function test_cms_content_uses_english_with_field_level_fallback_and_keeps_original_values(): void
    {
        $page = Page::create([
            'title' => 'Tiêu đề gốc', 'slug' => 'bilingual-page', 'excerpt' => 'Mô tả gốc',
            'content' => '<p>Nội dung gốc</p>', 'is_active' => true,
            'translations' => ['en' => ['title' => 'English page title', 'content' => '<p>English content</p>', 'excerpt' => '']],
        ]);
        $this->get(route('slug.show', [$page->slug, 'lang' => 'en']))->assertOk()
            ->assertSee('English page title')->assertSee('English content')->assertSee('Mô tả gốc');
        $this->assertSame('Tiêu đề gốc', $page->getRawOriginal('title'));
        $this->get(route('slug.show', $page->slug))->assertOk()->assertSee('Tiêu đề gốc')->assertSee('Nội dung gốc');

        $service = new Service(['name' => 'Dịch vụ gốc', 'short_description' => 'Mô tả gốc',
            'translations' => ['en' => ['name' => 'English service', 'short_description' => 'English summary', 'slug' => 'must-not-translate']]]);
        app()->setLocale('en');
        $this->assertSame('English service', $service->name);
        $this->assertSame('English summary', $service->short_description);
        $this->assertNull($service->slug);
    }

    public function test_english_search_ajax_and_pagination_keep_the_selected_locale(): void
    {
        for ($i = 0; $i < 10; $i++) {
            Course::create(['title' => 'Khóa học '.$i, 'slug' => 'course-'.$i, 'price' => 'Liên hệ', 'is_active' => true,
                'translations' => ['en' => ['title' => 'I18nSearchToken course '.$i]]]);
        }
        $url = route('services.dao-tao', ['lang' => 'en', 'search' => 'I18nSearchToken']);
        $this->get($url)->assertOk()->assertSee('I18nSearchToken course')->assertSee('name="lang" value="en"', false)
            ->assertSee('lang=en', false)->assertSee('matching courses');
        $response = $this->getJson($url)->assertOk()->assertJsonPath('count', 10);
        $this->assertStringContainsString('I18nSearchToken course', $response->json('html'));
        $this->assertStringContainsString('lang=en', $response->json('html'));
    }

    public function test_validation_registration_redirects_and_success_stay_in_english(): void
    {
        $course = Course::create(['title' => 'Khóa học', 'slug' => 'register-en', 'price' => 'Liên hệ', 'is_active' => true]);
        $this->postJson(route('course-registration.store', [$course, 'lang' => 'en']), [])
            ->assertUnprocessable()->assertJsonPath('errors.company_name.0', 'Please enter the company name.');
        $this->postJson(route('contact.store', ['lang' => 'en']), [])
            ->assertUnprocessable()->assertJsonPath('errors.name.0', 'Please enter your full name.');

        $response = $this->post(route('course-registration.store', [$course, 'lang' => 'en']), [
            'company_name' => 'Example', 'company_address' => 'HCMC', 'tax_code' => '0123456789',
            'attendee_count' => 1, 'attendees' => [['full_name' => 'Jane', 'email' => 'jane@example.com', 'phone' => '0901234567']],
            'invoice_email' => 'billing@example.com', 'consent' => '1',
        ]);
        $registration = CourseRegistration::firstOrFail();
        $response->assertRedirect(route('course-registration.success', [$registration, 'lang' => 'en']));
        $this->get($response->headers->get('Location'))->assertOk()->assertSee('Registration successful');
        $this->get(route('course-registration.index', ['course' => $course->slug, 'lang' => 'en']))
            ->assertRedirect(route('course-registration.create', [$course, 'source' => 'qr', 'lang' => 'en']));
    }

    public function test_urls_preserve_filters_fragments_and_external_targets(): void
    {
        $this->get(route('contact', ['lang' => 'en']));
        $this->assertSame('/tin-tuc?tag=ESG&page=2&lang=en#news', website_url('/tin-tuc?tag=ESG&page=2#news'));
        $this->assertSame('/tin-tuc?page=2#news', website_url('/tin-tuc?lang=en&page=2#news', 'vi'));
        foreach (['mailto:info@greeco.vn', 'tel:0901234567', '#content', 'https://example.com/?lang=vi'] as $url) {
            $this->assertSame($url, website_url($url));
        }
        $this->get(route('sitemap'))->assertOk()
            ->assertSee('<loc>'.route('home', ['lang' => 'en']).'</loc>', false)
            ->assertSee('xhtml:link rel="alternate" hreflang="en"', false);
    }

    public function test_settings_select_language_without_leaking_between_requests(): void
    {
        Setting::create(['key' => 'work_hours', 'value' => 'Giờ Việt Nam', 'value_en' => 'English working hours', 'type' => 'text', 'group' => 'contact']);
        $this->get(route('contact', ['lang' => 'en']))->assertOk()->assertSee('English working hours');
        $this->get(route('contact'))->assertOk()->assertSee('Giờ Việt Nam')->assertDontSee('English working hours');
    }
}

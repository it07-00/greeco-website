<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Setting;

class ContactButtonsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Setting::clearCache();
    }

    public function test_floating_contact_buttons_are_rendered_with_correct_links(): void
    {
        // Seed contact info
        Setting::query()->updateOrCreate(['key' => 'phone'], [
            'key' => 'phone',
            'value' => '09369 96390',
            'type' => 'text',
            'group' => 'contact',
        ]);
        
        Setting::query()->updateOrCreate(['key' => 'facebook_url'], [
            'key' => 'facebook_url',
            'value' => 'https://www.facebook.com/greecoofficial?locale=vi_VN',
            'type' => 'text',
            'group' => 'social',
        ]);

        Setting::clearCache();

        $response = $this->get('/');
        $response->assertStatus(200);

        // Verify phone link (no spaces)
        $response->assertSee('href="tel:0936996390"', false);

        // Verify Zalo link (falls back to phone-based Zalo URL)
        $response->assertSee('href="https://zalo.me/0936996390"', false);

        // Verify Messenger link (auto-extracted from Facebook URL)
        $response->assertSee('href="https://m.me/greecoofficial"', false);
    }

    public function test_floating_contact_buttons_render_single_phone_link_directly(): void
    {
        Setting::query()->updateOrCreate(['key' => 'phone'], [
            'key' => 'phone',
            'value' => '09369 96390',
            'type' => 'text',
            'group' => 'contact',
        ]);
        Setting::query()->where('key', 'phone_2')->delete();

        Setting::clearCache();

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('href="tel:0936996390"', false);
        $response->assertDontSee('class="contact-btn phone-btn multi-phone-btn"', false);
    }

    public function test_floating_contact_buttons_render_multi_phone_submenu(): void
    {
        Setting::query()->updateOrCreate(['key' => 'phone'], [
            'key' => 'phone',
            'value' => '09369 96390',
            'type' => 'text',
            'group' => 'contact',
        ]);
        Setting::query()->updateOrCreate(['key' => 'phone_2'], [
            'key' => 'phone_2',
            'value' => '09000 00000',
            'type' => 'text',
            'group' => 'contact',
        ]);

        Setting::clearCache();

        $this->assertEquals('09000 00000', Setting::getValue('phone_2'));

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('multi-phone-btn', false);
        $response->assertSee('href="tel:0936996390"', false);
        $response->assertSee('href="tel:0900000000"', false);
    }
}

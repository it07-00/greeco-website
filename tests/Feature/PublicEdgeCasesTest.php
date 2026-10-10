<?php

namespace Tests\Feature;

use App\Filament\Resources\CourseRegistrations\Pages\EditCourseRegistration;
use App\Models\Course;
use App\Models\CourseRegistration;
use App\Models\Post;
use App\Models\SeoRedirect;
use App\Models\Setting;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Livewire\Livewire;
use Tests\TestCase;

class PublicEdgeCasesTest extends TestCase
{
    use RefreshDatabase;

    public function test_scheduled_and_draft_posts_stay_hidden_across_public_consumers(): void
    {
        $this->freezeTime();
        $visible = Post::create(['title' => 'Published marker', 'slug' => 'published-marker', 'is_published' => true, 'is_indexable' => true, 'published_at' => now()]);
        $scheduled = Post::create(['title' => 'Scheduled marker', 'slug' => 'scheduled-marker', 'is_published' => true, 'is_indexable' => true, 'published_at' => now()->addDay()]);
        $draft = Post::create(['title' => 'Draft marker', 'slug' => 'draft-marker', 'is_published' => false, 'published_at' => now()->subDay()]);

        foreach (['home', 'posts.index', 'sitemap'] as $route) {
            $this->get(route($route))->assertOk()->assertDontSee($scheduled->slug)->assertDontSee($draft->slug);
        }

        $this->get(route('slug.show', $visible->slug))->assertOk()->assertDontSee($scheduled->slug);
        $this->get(route('slug.show', $scheduled->slug))->assertNotFound();
        $this->get(route('slug.show', $draft->slug))->assertNotFound();
        $this->assertSame(0, $scheduled->fresh()->views_count);

        $this->travel(2)->days();
        $this->get(route('slug.show', $scheduled->slug))->assertOk();
    }

    public function test_view_count_does_not_change_the_article_modification_time(): void
    {
        $post = Post::create(['title' => 'Stable modification time', 'slug' => 'stable-modification-time', 'is_published' => true]);
        $modifiedAt = $post->updated_at->toIso8601String();
        $this->travel(1)->days();
        $this->get(route('slug.show', $post->slug))->assertOk();
        $this->assertSame(1, $post->fresh()->views_count);
        $this->assertSame($modifiedAt, $post->fresh()->updated_at->toIso8601String());
    }

    public function test_validation_redirect_is_preserved_when_debug_is_disabled(): void
    {
        config(['app.debug' => false]);
        $this->from(route('contact'))->post(route('contact.store'), ['name' => ''])
            ->assertRedirect(route('contact'))->assertSessionHasErrors('name');
    }

    public function test_json_validation_and_not_found_responses_remain_json(): void
    {
        config(['app.debug' => false]);
        $this->getJson(route('services.dao-tao', ['search' => ['invalid']]))
            ->assertUnprocessable()->assertJsonValidationErrors('search');
        $this->getJson('/missing-content-for-json')->assertNotFound()->assertJsonStructure(['message']);
    }

    public function test_custom_error_response_preserves_http_headers(): void
    {
        config(['app.debug' => false]);
        Route::get('/thu-vien', fn () => abort(503, 'Unavailable', ['Retry-After' => '120']));
        $this->get('/thu-vien')->assertStatus(503)->assertHeader('Retry-After', '120');
    }

    public function test_post_tag_array_is_a_validation_error(): void
    {
        $this->getJson(route('posts.index', ['tag' => ['invalid']]))
            ->assertUnprocessable()->assertJsonValidationErrors('tag');
    }

    public function test_contact_prefill_and_invalid_old_input_do_not_crash_the_page(): void
    {
        $this->get(route('contact', ['message' => ['invalid']]))->assertOk();
        $this->withSession(['_old_input' => ['name' => ['invalid'], 'email' => ['invalid'], 'phone' => ['invalid'], 'message' => ['invalid']]])
            ->get(route('contact'))->assertOk();
    }

    public function test_registration_accepts_sparse_attendee_indices_and_strips_extra_keys(): void
    {
        $course = $this->course();
        $other = $this->course('other-course');
        $data = $this->registrationData();
        $data['attendees'] = [3 => [...$data['attendees'][0], 'course_registration_id' => 999, 'id' => 999]];

        $this->post(route('course-registration.store', $course), $data)->assertRedirect();
        $registration = CourseRegistration::sole();
        $this->assertSame($course->id, $registration->course_id);
        $this->assertSame('Primary attendee', $registration->contact_name);
        $this->assertSame($registration->id, $registration->attendees()->sole()->course_registration_id);
        $this->assertNotSame($other->id, $registration->course_id);
    }

    public function test_invalid_registration_input_can_be_redisplayed(): void
    {
        $course = $this->course();
        $data = $this->registrationData();
        $data['attendees'] = 'invalid';
        $data['company_name'] = ['invalid'];
        $this->from(route('course-registration.create', $course))
            ->post(route('course-registration.store', $course), $data)
            ->assertRedirect()->assertSessionHasErrors(['attendees', 'company_name']);
        $this->get(route('course-registration.create', $course))->assertOk();
    }

    public function test_deleting_a_setting_invalidates_the_cached_value(): void
    {
        $setting = Setting::create(['key' => 'test-cache', 'value' => 'old', 'type' => 'text', 'group' => 'general']);
        $this->assertSame('old', setting('test-cache'));
        $setting->delete();
        $this->assertSame('fallback', setting('test-cache', 'fallback'));
    }

    public function test_redirect_cycles_do_not_trap_public_requests(): void
    {
        SeoRedirect::create(['old_url' => '/lien-he', 'new_url' => '/lien-he', 'is_active' => true, 'status_code' => 301]);
        $this->get('/lien-he')->assertOk();

        SeoRedirect::query()->delete();
        SeoRedirect::create(['old_url' => '/lien-he', 'new_url' => '/thu-vien', 'is_active' => true, 'status_code' => 301]);
        SeoRedirect::create(['old_url' => '/thu-vien', 'new_url' => '/lien-he', 'is_active' => true, 'status_code' => 301]);
        $this->get('/lien-he')->assertOk();
        $this->get('/thu-vien')->assertOk();
    }

    public function test_valid_seo_redirect_still_works(): void
    {
        SeoRedirect::create(['old_url' => '/old-contact', 'new_url' => '/lien-he', 'is_active' => true, 'status_code' => 301]);
        $this->get('/old-contact')->assertStatus(301)->assertRedirect('/lien-he');
    }

    public function test_seo_redirects_do_not_intercept_form_submissions(): void
    {
        SeoRedirect::create(['old_url' => '/lien-he', 'new_url' => '/thu-vien', 'is_active' => true, 'status_code' => 301]);
        $this->post(route('contact.store'), ['name' => 'Contact test'])->assertRedirect();
        $this->assertDatabaseHas('contacts', ['name' => 'Contact test']);
    }

    public function test_admin_note_save_preserves_the_registration_source(): void
    {
        $course = $this->course();
        $this->post(route('course-registration.store', $course), [...$this->registrationData(), 'source' => 'qr'])->assertRedirect();
        $registration = CourseRegistration::sole();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());

        Livewire::test(EditCourseRegistration::class, ['record' => $registration->getRouteKey()])
            ->fillForm(['admin_notes' => 'Processed'])->call('save')->assertHasNoFormErrors();
        $this->assertSame('qr', $registration->fresh()->source);
        $this->assertSame('Processed', $registration->fresh()->admin_notes);
    }

    public function test_admin_access_works_in_production_and_guests_stay_out(): void
    {
        config(['app.env' => 'production', 'app.debug' => false]);
        $this->get('/admin')->assertRedirect('/admin/login');
        $this->actingAs(User::factory()->create());
        $this->get('/admin')->assertOk();
    }

    public function test_contact_rejects_text_longer_than_database_columns(): void
    {
        $this->post(route('contact.store'), ['name' => str_repeat('a', 192), 'subject' => str_repeat('b', 192)])
            ->assertSessionHasErrors(['name', 'subject']);
        $this->assertDatabaseCount('contacts', 0);
    }

    public function test_registration_rejects_text_longer_than_database_columns(): void
    {
        $course = $this->course();
        $data = $this->registrationData();
        $data['company_name'] = str_repeat('a', 192);
        $data['attendees'][0]['full_name'] = str_repeat('b', 192);
        $this->post(route('course-registration.store', $course), $data)
            ->assertSessionHasErrors(['company_name', 'attendees.0.full_name']);
        $this->assertDatabaseCount('course_registrations', 0);
    }

    private function course(string $slug = 'edge-course'): Course
    {
        return Course::create(['title' => $slug, 'slug' => $slug, 'price' => 'Liên hệ', 'is_active' => true]);
    }

    private function registrationData(): array
    {
        return [
            'company_name' => 'Test company', 'company_address' => 'Test address', 'tax_code' => '0312345678',
            'attendee_count' => 1,
            'attendees' => [['full_name' => 'Primary attendee', 'email' => 'primary@example.com', 'phone' => '0901000001']],
            'invoice_email' => 'invoice@example.com', 'source' => 'website', 'consent' => '1',
        ];
    }
}

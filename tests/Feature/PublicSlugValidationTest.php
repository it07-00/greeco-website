<?php

namespace Tests\Feature;

use App\Filament\Resources\Courses\Pages\CreateCourse;
use App\Filament\Resources\Pages\Pages\CreatePage;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Projects\Pages\CreateProject;
use App\Filament\Resources\Services\Pages\CreateService;
use App\Models\Course;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PublicSlugValidationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Filament::setCurrentPanel(Filament::getPanel('admin'));
        $this->actingAs(User::factory()->create());
    }

    #[DataProvider('contentResources')]
    public function test_all_content_forms_reject_slugs_owned_by_another_content_type(string $pageClass, string $modelClass, string $titleField): void
    {
        $ownerClass = $modelClass === Course::class ? Post::class : Course::class;
        $ownerClass::create(['title' => 'Existing content', 'slug' => 'shared-public-slug', 'price' => 'Liên hệ']);

        Livewire::test($pageClass)
            ->fillForm([$titleField => 'New content', 'slug' => 'shared-public-slug', 'price' => 'Liên hệ'])
            ->call('create')->assertHasFormErrors(['slug']);
        $this->assertFalse($modelClass::where('slug', 'shared-public-slug')->exists());
    }

    #[DataProvider('contentResources')]
    public function test_content_forms_reject_reserved_routes(string $pageClass, string $modelClass, string $titleField): void
    {
        Livewire::test($pageClass)
            ->fillForm([$titleField => 'New content', 'slug' => 'lien-he', 'price' => 'Liên hệ'])
            ->call('create')->assertHasFormErrors(['slug']);
        $this->assertFalse($modelClass::where('slug', 'lien-he')->exists());
    }

    public function test_normalized_slug_is_checked_before_saving(): void
    {
        Post::create(['title' => 'Existing', 'slug' => 'normalized-slug']);
        Livewire::test(CreatePost::class)
            ->fillForm(['title' => 'New', 'slug' => 'Normalized Slug'])
            ->call('create')->assertHasFormErrors(['slug']);
    }

    public function test_editing_post_can_retain_its_own_slug(): void
    {
        $post = Post::create(['title' => 'Existing', 'slug' => 'own-post-slug']);
        Livewire::test(EditPost::class, ['record' => $post->getRouteKey()])
            ->fillForm(['title' => 'Updated', 'slug' => $post->slug])
            ->call('save')->assertHasNoFormErrors();
        $this->assertSame('Updated', $post->fresh()->title);
    }

    public function test_about_page_can_keep_its_dedicated_route_slug(): void
    {
        Livewire::test(CreatePage::class)
            ->fillForm(['title' => 'About content', 'slug' => 'gioi-thieu'])
            ->call('create')->assertHasNoFormErrors();
        $this->get('/gioi-thieu')->assertOk()->assertSee('About content');
    }

    public function test_post_form_rejects_text_longer_than_database_columns(): void
    {
        Livewire::test(CreatePost::class)
            ->fillForm(['title' => str_repeat('a', 192), 'slug' => 'long-title-test', 'excerpt' => str_repeat('b', 192)])
            ->call('create')->assertHasFormErrors(['title', 'excerpt']);
        $this->assertDatabaseCount('posts', 0);
    }

    public function test_course_retains_support_for_longer_columns(): void
    {
        $title = str_repeat('a', 300);
        $slug = str_repeat('b', 300);
        Livewire::test(CreateCourse::class)->fillForm(['title' => $title, 'slug' => $slug, 'price' => 'Liên hệ', 'curriculum' => []])
            ->call('create')->assertHasNoFormErrors();
        $this->assertDatabaseHas('courses', ['title' => $title, 'slug' => $slug]);
    }

    public static function contentResources(): array
    {
        return [
            'post' => [CreatePost::class, Post::class, 'title'],
            'course' => [CreateCourse::class, Course::class, 'title'],
            'project' => [CreateProject::class, Project::class, 'title'],
            'service' => [CreateService::class, Service::class, 'name'],
            'page' => [CreatePage::class, Page::class, 'title'],
        ];
    }
}

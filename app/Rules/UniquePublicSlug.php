<?php

namespace App\Rules;

use App\Models\Course;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class UniquePublicSlug implements ValidationRule
{
    public function __construct(private string $modelClass, private ?Model $record = null) {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $slug = Str::slug((string) $value);
        if ($slug === '') {
            $fail('Slug phải chứa chữ hoặc số.');

            return;
        }

        $reserved = ['admin', 'login', 'register', 'dashboard', 'assets', 'build', 'storage', 'up', 'livewire', 'filament'];
        foreach (Route::getRoutes() as $route) {
            $uri = $route->uri();
            if ($route->getDomain() === null && ! str_contains($uri, '/') && ! str_contains($uri, '{')) {
                $reserved[] = $uri;
            }
        }

        // This dedicated route reads the CMS Page rather than the universal resolver.
        $isAboutPage = $this->modelClass === Page::class && $slug === 'gioi-thieu';
        if (! $isAboutPage && in_array($slug, $reserved, true)) {
            $fail('Slug này đã được sử dụng bởi một trang hệ thống.');

            return;
        }

        foreach ([Post::class, Course::class, Project::class, Service::class, Page::class] as $modelClass) {
            $query = $modelClass::query()->where('slug', $slug);
            if ($this->record instanceof $modelClass && $this->record->exists) {
                $query->whereKeyNot($this->record->getKey());
            }
            if ($query->exists()) {
                $fail('Slug này đã được sử dụng bởi một nội dung khác.');

                return;
            }
        }
    }
}

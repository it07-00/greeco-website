<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use Illuminate\View\View;

/**
 * Universal slug resolver — looks up a slug across all content types
 * and renders the appropriate detail view.
 *
 * Resolution order: Post → Course → Project → Service → Page.
 */
class SlugController extends Controller
{
    public function show(string $slug): View
    {
        // 1. Posts (tin tức)
        if ($post = Post::where('slug', $slug)->published()->first()) {
            Post::withoutTimestamps(fn () => $post->increment('views_count'));
            $post->refresh();

            $relatedPosts = Post::query()
                ->where('id', '!=', $post->id)
                ->published()
                ->where('post_category_id', $post->post_category_id)
                ->limit(6)
                ->get();

            return view('posts.show', compact('post', 'relatedPosts'));
        }

        // 2. Courses (đào tạo & bồi dưỡng)
        if ($course = Course::where('slug', $slug)->where('is_active', true)->first()) {
            $chapters = $course->curriculum ?? [];

            return view('training.show', compact('course', 'chapters'));
        }

        // 3. Projects (dự án)
        if ($project = Project::where('slug', $slug)->where('is_active', true)->first()) {
            $relatedProjects = Project::query()
                ->where('id', '!=', $project->id)
                ->where('is_active', true)
                ->limit(6)
                ->get();

            return view('projects.show', compact('project', 'relatedProjects'));
        }

        // 4. Services (dịch vụ)
        if ($service = Service::where('slug', $slug)->where('is_active', true)->first()) {
            $relatedServices = Service::query()
                ->where('id', '!=', $service->id)
                ->where('is_active', true)
                ->where('service_category_id', $service->service_category_id)
                ->limit(6)
                ->get();

            return view('services.show', compact('service', 'relatedServices'));
        }

        // 5. Pages (CMS pages — catch-all)
        $page = Page::where('slug', $slug)->where('is_active', true)->firstOrFail();

        return view('pages.show', compact('page'));
    }
}

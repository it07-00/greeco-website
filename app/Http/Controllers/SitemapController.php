<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Page;
use App\Models\Post;
use App\Models\Project;
use App\Models\Service;
use App\Support\SitemapRoutes;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $staticRoutes = SitemapRoutes::static();

        $services = Service::query()
            ->where('is_active', true)
            ->where('is_indexable', true)
            ->orderBy('sort_order')
            ->get();

        $courses = Course::query()
            ->where('is_active', true)
            ->where('is_indexable', true)
            ->orderBy('sort_order')
            ->get();

        $posts = Post::query()
            ->where('is_published', true)
            ->where('is_indexable', true)
            ->latest('published_at')
            ->get();

        $projects = Project::query()
            ->where('is_active', true)
            ->where('is_indexable', true)
            ->orderBy('sort_order')
            ->get();

        $pages = Page::query()
            ->where('is_active', true)
            ->where('is_indexable', true)
            ->whereNotIn('slug', SitemapRoutes::staticPageSlugs())
            ->orderBy('sort_order')
            ->get();

        return response()
            ->view('sitemap.index', compact('staticRoutes', 'services', 'courses', 'posts', 'projects', 'pages'))
            ->header('Content-Type', 'text/xml');
    }
}

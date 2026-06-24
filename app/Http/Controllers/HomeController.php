<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Partner;
use App\Models\Post;
use App\Models\Project;
use App\Models\TeamMember;
use App\Support\MainServiceCategories;

class HomeController extends Controller
{
    public function index()
    {
        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $serviceCategories = MainServiceCategories::get();

        $latestPosts = Post::query()
            ->where('is_published', true)
            ->latest('published_at')
            ->limit(3)
            ->get();

        $featuredProjects = Project::query()
            ->where('is_active', true)
            ->where('is_featured', true)
            ->orderBy('sort_order')
            ->limit(6)
            ->get();

        $partners = Partner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        $teamMembers = TeamMember::query()
            ->where('is_active', true)
            ->orderBy('order_column')
            ->limit(8)
            ->get();

        return view('pages.home', compact(
            'banners',
            'serviceCategories',
            'latestPosts',
            'featuredProjects',
            'partners',
            'teamMembers'
        ));
    }
}

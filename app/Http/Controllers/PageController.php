<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function about()
    {
        $page = Page::query()
            ->where('slug', 'gioi-thieu')
            ->where('is_active', true)
            ->firstOrFail();

        $teamMembers = \App\Models\TeamMember::where('is_active', true)->orderBy('order_column')->get();

        return view('pages.about', compact('page', 'teamMembers'));
    }

    public function team()
    {
        $teamMembers = \App\Models\TeamMember::where('is_active', true)->orderBy('order_column')->get();
        return view('pages.team', compact('teamMembers'));
    }

    public function show(Page $page)
    {
        abort_unless($page->is_active, 404);

        return view('pages.show', compact('page'));
    }

    public function library()
    {
        $documents = \App\Models\Document::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('id', 'desc')
            ->get();

        return view('pages.library', compact('documents'));
    }

    public function gallery()
    {
        $galleries = \App\Models\Gallery::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('pages.gallery', compact('galleries'));
    }
}

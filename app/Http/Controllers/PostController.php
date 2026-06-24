<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::query()
            ->with('category')
            ->where('is_published', true);

        $activeTag = $request->input('tag');

        if ($request->filled('tag')) {
            $query->where(function ($q) use ($activeTag) {
                $q->whereJsonContains('tags', $activeTag)
                    ->orWhere('title', 'like', '%'.$activeTag.'%')
                    ->orWhere('excerpt', 'like', '%'.$activeTag.'%')
                    ->orWhere('content', 'like', '%'.$activeTag.'%')
                    ->orWhere('meta_keywords', 'like', '%'.$activeTag.'%');
            });
        }

        $posts = $query->latest('published_at')->paginate(9);

        if ($activeTag) {
            $posts->appends(['tag' => $activeTag]);
        }

        $categories = PostCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('posts.index', compact('posts', 'categories', 'activeTag'));
    }

    public function show(Post $post)
    {
        abort_unless($post->is_published, 404);

        $post->increment('views_count');
        $post->refresh();

        $relatedPosts = Post::query()
            ->where('id', '!=', $post->id)
            ->where('is_published', true)
            ->where('post_category_id', $post->post_category_id)
            ->limit(6)
            ->get();

        return view('posts.show', compact('post', 'relatedPosts'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\PostCategory;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate(['tag' => ['nullable', 'string', 'max:100']]);
        $query = Post::query()
            ->with('category')
            ->published();

        $activeTag = trim($filters['tag'] ?? '');

        if ($activeTag !== '') {
            $query->where(function ($q) use ($activeTag) {
                $q->whereJsonContains('tags', $activeTag)
                    ->orWhere('title', 'like', '%'.$activeTag.'%')
                    ->orWhere('excerpt', 'like', '%'.$activeTag.'%')
                    ->orWhere('content', 'like', '%'.$activeTag.'%')
                    ->orWhere('meta_keywords', 'like', '%'.$activeTag.'%')
                    ->when(app()->getLocale() === 'en', function ($q) use ($activeTag) {
                        $q->orWhereJsonContains('translations->en->tags', $activeTag)
                            ->orWhere('translations->en->title', 'like', '%'.$activeTag.'%')
                            ->orWhere('translations->en->excerpt', 'like', '%'.$activeTag.'%')
                            ->orWhere('translations->en->content', 'like', '%'.$activeTag.'%')
                            ->orWhere('translations->en->meta_keywords', 'like', '%'.$activeTag.'%');
                    });
            });
        }

        $posts = $query->latest('published_at')->paginate(9)->withQueryString();

        if ($activeTag !== '') {
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
        abort_unless(Post::published()->whereKey($post->getKey())->exists(), 404);

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
}

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class CourseController extends Controller
{
    /**
     * Display the course catalog page.
     */
    public function index(Request $request): View|JsonResponse
    {
        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:255'],
        ]);
        $search = trim((string) ($filters['search'] ?? ''));
        $category = trim((string) ($filters['category'] ?? ''));

        $categories = CourseCategory::query()
            ->withCount([
                'courses' => fn($query) => $query->where('is_active', true),
            ])
            ->orderBy('sort_order')
            ->get();

        $courses = Course::query()
            ->with('category')
            ->where('is_active', true)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query
                        ->where('title', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%")
                        ->orWhere('tag', 'like', "%{$search}%")
                        ->orWhereHas('category', fn($categoryQuery) => $categoryQuery->where('name', 'like', "%{$search}%"));
                });
            })
            ->when($category !== '', function ($query) use ($category) {
                $query->whereHas('category', fn($categoryQuery) => $categoryQuery->where('slug', $category));
            })
            ->orderBy('sort_order')
            ->paginate(9)
            ->withQueryString();

        $programs = $courses->through(function (Course $course) {
            return [
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'image' => $course->image ? Storage::url($course->image) : asset('assets/images/services/iso-9001-course.png'),
                'tag' => $course->tag,
                'category_name' => $course->category?->name,
                'badge_color' => $course->badge_color ?? 'bg-success',
                'price' => !empty($course->price) ? $course->price : 'Liên hệ',
                'original_price' => $course->original_price,
                'discount' => $course->discount,
            ];
        });

        if ($request->expectsJson()) {
            $suggestionPrograms = collect($programs->items())->take(5);

            return response()->json([
                'html' => view('training.partials.course-results', compact('programs'))->render(),
                'suggestions_html' => view('training.partials.course-search-suggestions', compact(
                    'suggestionPrograms',
                    'programs',
                    'search',
                    'category'
                ))->render(),
                'count' => $programs->total(),
                'search' => $search,
                'category' => $category,
            ]);
        }

        return view('training.index', compact('categories', 'programs', 'search', 'category'));
    }

    /**
     * Display the course detail page.
     */
    public function show($slug)
    {
        $course = Course::query()
            ->where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Map chapters/lessons to match layout expectations if structure exists
        $chapters = $course->curriculum ?? [];

        return view('training.show', compact('course', 'chapters'));
    }
}

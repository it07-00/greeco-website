<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display the course catalog page.
     */
    public function index()
    {
        $categories = CourseCategory::query()
            ->orderBy('sort_order')
            ->get();

        $courses = Course::query()
            ->with('category')
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        // Map to the existing structure for views
        $programs = $courses->map(function ($course) {
            return [
                'title' => $course->title,
                'slug' => $course->slug,
                'description' => $course->description,
                'image' => $course->image ?? 'assets/images/services/iso-9001-course.png',
                'tag' => $course->tag,
                'category_name' => $course->category?->name,
                'badge_color' => $course->badge_color ?? 'bg-success',
                'price' => $course->price,
                'original_price' => $course->original_price,
                'discount' => $course->discount,
            ];
        });

        return view('training.index', compact('categories', 'programs'));
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

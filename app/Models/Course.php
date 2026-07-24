<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_category_id',
        'title',
        'slug',
        'description',
        'content',
        'image',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'canonical_url',
        'og_image',
        'tag',
        'badge_color',
        'price',
        'original_price',
        'discount',
        'instructor_name',
        'instructor_role',
        'instructor_image',
        'instructor_description',
        'curriculum',
        'is_indexable',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'curriculum' => 'array',
        'is_indexable' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(CourseCategory::class, 'course_category_id');
    }

    protected function slug(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            set: fn (?string $value) => \Illuminate\Support\Str::slug($value ?? ''),
        );
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'post_category_id',
        'title',
        'slug',
        'excerpt',
        'content',
        'content_type',
        'pdf_path',
        'pdf_layout',
        'pdf_viewer_type',
        'thumbnail',
        'meta_title',
        'meta_description',
        'meta_keywords',
        'tags',
        'canonical_url',
        'og_image',
        'is_featured',
        'is_indexable',
        'is_published',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_indexable' => 'boolean',
        'is_published' => 'boolean',
        'views_count' => 'integer',
        'published_at' => 'datetime',
        'tags' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(PostCategory::class, 'post_category_id');
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    protected function slug(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            set: fn (?string $value) => \Illuminate\Support\Str::slug($value ?? ''),
        );
    }
}

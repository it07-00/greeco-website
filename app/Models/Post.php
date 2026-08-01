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

    protected static function booted(): void
    {
        static::saving(function (Post $post) {
            if (blank($post->slug) && filled($post->title)) {
                $post->slug = \Illuminate\Support\Str::slug($post->title);
            }

            if (blank($post->meta_title) && filled($post->title)) {
                $post->meta_title = \App\Filament\Resources\Posts\Schemas\PostForm::suggestMetaTitle($post->title);
            }

            if (blank($post->meta_description)) {
                $post->meta_description = \App\Filament\Resources\Posts\Schemas\PostForm::suggestMetaDescription($post->excerpt, $post->content);
            }

            if (blank($post->meta_keywords)) {
                $post->meta_keywords = \App\Filament\Resources\Posts\Schemas\PostForm::suggestMetaKeywords($post->tags, $post->title);
            }

            if (blank($post->og_image) && filled($post->thumbnail)) {
                $post->og_image = $post->thumbnail;
            }
        });
    }
}

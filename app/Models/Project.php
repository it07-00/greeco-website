<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'thumbnail',
        'client_name',
        'location',
        'started_at',
        'completed_at',
        'meta_title',
        'meta_description',
        'canonical_url',
        'og_image',
        'is_featured',
        'is_indexable',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'is_indexable' => 'boolean',
        'is_active' => 'boolean',
        'sort_order' => 'integer',
        'started_at' => 'date',
        'completed_at' => 'date',
    ];

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

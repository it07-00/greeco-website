<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamMember extends Model
{
    use HasFactory;
    use HasTranslations;

    protected $fillable = [
        'name',
        'role',
        'description',
        'image',
        'facebook_url',
        'linkedin_url',
        'order_column',
        'is_active',
    ];
}

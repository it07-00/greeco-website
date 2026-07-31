<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RegistrationAttendee extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name',
        'job_title',
        'email',
        'phone',
    ];

    public function registration(): BelongsTo
    {
        return $this->belongsTo(CourseRegistration::class, 'course_registration_id');
    }
}

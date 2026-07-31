<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CourseRegistration extends Model
{
    use HasFactory;

    public const STATUSES = [
        'new' => 'Mới đăng ký',
        'contacted' => 'Đã liên hệ',
        'confirmed' => 'Đã xác nhận',
        'cancelled' => 'Đã hủy',
    ];

    protected $fillable = [
        'course_id',
        'code',
        'company_name',
        'company_address',
        'tax_code',
        'contact_name',
        'contact_email',
        'contact_phone',
        'attendee_count',
        'invoice_email',
        'consultation_question',
        'source',
        'status',
        'is_read',
        'admin_notes',
        'consent_at',
    ];

    protected $casts = [
        'attendee_count' => 'integer',
        'is_read' => 'boolean',
        'consent_at' => 'datetime',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function attendees(): HasMany
    {
        return $this->hasMany(RegistrationAttendee::class);
    }

    public function getRouteKeyName(): string
    {
        return 'code';
    }
}

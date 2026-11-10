<?php

namespace App\Models\EduQuiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    protected $table = 'eduquiz_sessions';

    protected $guarded = ['id'];

    public function participants(): HasMany
    {
        return $this->hasMany(Participant::class, 'session_id');
    }

    protected function casts(): array
    {
        return [
            'questions' => 'array',
            'requires_invite' => 'boolean',
            'deadline' => 'immutable_datetime',
            'finished_at' => 'immutable_datetime',
            'anonymized_at' => 'immutable_datetime',
        ];
    }

    public function phase(): string
    {
        if ($this->status === 'open' && $this->deadline <= now()) {
            return 'closed';
        }

        return $this->status;
    }
}

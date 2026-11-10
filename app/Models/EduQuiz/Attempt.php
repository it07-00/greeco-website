<?php

namespace App\Models\EduQuiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Attempt extends Model
{
    public $timestamps = false;

    protected $table = 'eduquiz_session_participants';

    protected $guarded = ['id'];

    protected $hidden = ['token_hash'];

    public function participant(): BelongsTo
    {
        return $this->belongsTo(Participant::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    public function result(): HasOne
    {
        return $this->hasOne(Result::class);
    }

    protected function casts(): array
    {
        return [
            'started_at' => 'immutable_datetime',
            'deadline' => 'immutable_datetime',
            'finished_at' => 'immutable_datetime',
        ];
    }
}

<?php

namespace App\Models\EduQuiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Participant extends Model
{
    protected $table = 'eduquiz_participants';

    protected $fillable = ['name', 'email', 'phone'];

    public function attempts(): HasMany
    {
        return $this->hasMany(Attempt::class);
    }
}

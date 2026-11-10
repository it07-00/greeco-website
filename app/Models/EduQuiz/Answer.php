<?php

namespace App\Models\EduQuiz;

use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    public $timestamps = false;

    protected $table = 'eduquiz_answers';

    protected $guarded = ['id'];

    protected function casts(): array
    {
        return ['choices' => 'array', 'correct' => 'boolean'];
    }
}

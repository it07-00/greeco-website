<?php

namespace App\Models\EduQuiz;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    public $timestamps = false;

    protected $table = 'eduquiz_options';

    protected $fillable = ['text', 'correct', 'position'];

    protected function casts(): array
    {
        return ['correct' => 'boolean'];
    }
}

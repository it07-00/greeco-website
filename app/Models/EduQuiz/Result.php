<?php

namespace App\Models\EduQuiz;

use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    public $timestamps = false;

    public $incrementing = false;

    protected $primaryKey = 'attempt_id';

    protected $table = 'eduquiz_results';

    protected $guarded = ['attempt_id'];
}

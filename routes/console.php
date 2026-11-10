<?php

use App\Services\EduQuiz\Play;
use App\Services\EduQuiz\Privacy;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(fn () => app(Play::class)->expireDue())->name('eduquiz-expire')->everyMinute()->withoutOverlapping();
Schedule::call(fn () => app(Privacy::class)->anonymizeDue())->name('eduquiz-privacy')->daily()->withoutOverlapping();

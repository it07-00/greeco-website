<?php

use App\Http\Controllers\EduQuiz\LibraryController;
use App\Http\Controllers\EduQuiz\ParticipantController;
use App\Http\Controllers\EduQuiz\ReportController;
use App\Http\Controllers\EduQuiz\RoomController;
use App\Http\Controllers\EduQuiz\StaffController;
use App\Services\EduQuiz\Access;
use App\Services\EduQuiz\ThrottlePlayer;
use Illuminate\Support\Facades\Route;

Route::prefix('eduquiz')->name('eduquiz.')->group(function () {
    Route::get('/', [ParticipantController::class, 'entry'])->name('join');
    Route::post('/tham-gia', [ParticipantController::class, 'join'])->middleware('throttle:120,1')->name('join.store');
    Route::prefix('{room:pin}')->where(['room' => '[0-9]{8}'])->group(function () {
        Route::get('/', [ParticipantController::class, 'entry'])->name('room');
        Route::post('/tham-gia', [ParticipantController::class, 'join'])->middleware('throttle:120,1')->name('room.join');
        Route::middleware(ThrottlePlayer::class)->group(function () {
            Route::get('/state', [ParticipantController::class, 'state'])->name('state');
            Route::post('/answer', [ParticipantController::class, 'answer'])->name('answer');
            Route::post('/submit', [ParticipantController::class, 'submit'])->name('submit');
            Route::post('/retry', [ParticipantController::class, 'retry'])->name('retry');
            Route::get('/image/{position}', [ParticipantController::class, 'image'])->whereNumber('position')->name('image');
        });
    });
});

Route::prefix('quan-ly-eduquiz')->name('eduquiz.staff.')->middleware(Access::class)->group(function () {
    Route::get('/', [StaffController::class, 'index'])->name('index');
    Route::get('/cai-dat', [StaffController::class, 'settings'])->name('settings');
    Route::post('/cai-dat', [StaffController::class, 'saveSettings'])->name('settings.save');

    Route::get('/cau-hoi', [LibraryController::class, 'questions'])->name('questions');
    Route::post('/cau-hoi/nhap', [LibraryController::class, 'import'])->name('questions.import');
    Route::get('/cau-hoi/mau', [LibraryController::class, 'template'])->name('questions.template');
    Route::get('/cau-hoi/soan/{question?}', [LibraryController::class, 'editQuestion'])->whereNumber('question')->name('questions.edit');
    Route::post('/cau-hoi/soan/{question?}', [LibraryController::class, 'saveQuestion'])->whereNumber('question')->name('questions.save');
    Route::post('/cau-hoi/{question}/nhan-ban', [LibraryController::class, 'duplicate'])->whereNumber('question')->name('questions.copy');
    Route::get('/cau-hoi/{question}/anh', [LibraryController::class, 'image'])->whereNumber('question')->name('questions.image');
    Route::delete('/cau-hoi', [LibraryController::class, 'deleteAllQuestions'])->name('questions.delete-all');
    Route::delete('/cau-hoi/{question}', [LibraryController::class, 'deleteQuestion'])->whereNumber('question')->name('questions.delete');

    Route::get('/bo-de', [LibraryController::class, 'quizzes'])->name('quizzes');
    Route::get('/bo-de/soan/{quiz?}', [LibraryController::class, 'editQuiz'])->whereNumber('quiz')->name('quizzes.edit');
    Route::post('/bo-de/soan/{quiz?}', [LibraryController::class, 'saveQuiz'])->whereNumber('quiz')->name('quizzes.save');
    Route::delete('/bo-de', [LibraryController::class, 'deleteAllQuizzes'])->name('quizzes.delete-all');
    Route::delete('/bo-de/{quiz}', [LibraryController::class, 'deleteQuiz'])->whereNumber('quiz')->name('quizzes.delete');
    Route::get('/khoa-dao-tao', [LibraryController::class, 'courses'])->name('courses');

    Route::get('/phong', [RoomController::class, 'index'])->name('rooms');
    Route::post('/phong', [RoomController::class, 'create'])->name('rooms.create');
    Route::prefix('phong/{room}')->whereNumber('room')->name('rooms.')->group(function () {
        Route::get('/', [RoomController::class, 'show'])->name('show');
        Route::get('/state', [RoomController::class, 'state'])->middleware('throttle:120,1')->name('state');
        Route::post('/control', [RoomController::class, 'control'])->name('control');
        Route::get('/qr.svg', [RoomController::class, 'qr'])->name('qr');
        Route::get('/image/{position}', [RoomController::class, 'image'])->whereNumber('position')->name('image');
        Route::post('/invite', [RoomController::class, 'invite'])->name('invite');
        Route::post('/anonymize', [RoomController::class, 'anonymize'])->name('anonymize');
    });

    Route::get('/hoc-vien', [ReportController::class, 'participants'])->name('participants');
    Route::get('/ket-qua', [ReportController::class, 'index'])->name('results');
    Route::get('/ket-qua/xuat/{format}', [ReportController::class, 'export'])->whereIn('format', ['xlsx', 'pdf'])->name('results.export');
    Route::get('/ket-qua/{attempt}', [ReportController::class, 'detail'])->whereNumber('attempt')->name('results.detail');
});

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('eduquiz_instructors', function (Blueprint $table) {
            $table->foreignId('user_id')->primary()->constrained()->cascadeOnDelete();
        });

        Schema::create('eduquiz_settings', function (Blueprint $table) {
            $table->unsignedTinyInteger('id')->primary();
            $table->unsignedSmallInteger('retention_days');
        });

        Schema::create('eduquiz_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->restrictOnDelete();
            $table->text('prompt');
            $table->text('explanation')->nullable();
            $table->string('image')->nullable();
            $table->unsignedSmallInteger('seconds');
            $table->timestamps();
        });

        Schema::create('eduquiz_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('eduquiz_questions')->cascadeOnDelete();
            $table->unsignedTinyInteger('position');
            $table->string('text', 500);
            $table->boolean('correct');
            $table->unique(['question_id', 'position']);
        });

        Schema::create('eduquiz_question_revisions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained('eduquiz_questions')->cascadeOnDelete();
            $table->foreignId('editor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('content');
            $table->timestamp('created_at');
        });

        Schema::create('eduquiz_quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->timestamps();
        });

        Schema::create('eduquiz_quiz_questions', function (Blueprint $table) {
            $table->foreignId('quiz_id')->constrained('eduquiz_quizzes')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('eduquiz_questions')->restrictOnDelete();
            $table->unsignedSmallInteger('position');
            $table->primary(['quiz_id', 'position']);
        });

        Schema::create('eduquiz_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('quiz_id')->constrained('eduquiz_quizzes')->restrictOnDelete();
            $table->foreignId('course_id')->nullable()->constrained()->nullOnDelete();
            $table->string('pin', 8)->unique();
            $table->string('title');
            $table->string('course_title')->nullable();
            $table->string('mode', 8);
            $table->string('status', 12)->default('lobby');
            $table->json('questions');
            $table->unsignedSmallInteger('current')->default(0);
            $table->unsignedInteger('version')->default(0);
            $table->unsignedInteger('duration_seconds');
            $table->unsignedTinyInteger('attempt_limit');
            $table->boolean('requires_invite');
            $table->timestamp('deadline')->nullable();
            $table->unsignedSmallInteger('paused_seconds')->nullable();
            $table->timestamp('finished_at')->nullable()->index();
            $table->timestamp('anonymized_at')->nullable();
            $table->timestamps();
            $table->index(['owner_id', 'created_at']);
        });

        Schema::create('eduquiz_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('eduquiz_sessions')->cascadeOnDelete();
            $table->string('name', 150);
            $table->string('email')->nullable();
            $table->string('phone', 16)->nullable();
            $table->timestamps();
            $table->unique(['session_id', 'email']);
            $table->unique(['session_id', 'phone']);
        });

        Schema::create('eduquiz_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('eduquiz_sessions')->cascadeOnDelete();
            $table->string('contact');
            $table->char('token_hash', 64)->unique();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
            $table->unique(['session_id', 'contact']);
        });

        Schema::create('eduquiz_session_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('participant_id')->constrained('eduquiz_participants')->cascadeOnDelete();
            $table->unsignedTinyInteger('attempt');
            $table->char('token_hash', 64)->nullable()->unique();
            $table->timestamp('started_at');
            $table->timestamp('deadline')->nullable()->index();
            $table->timestamp('finished_at')->nullable();
            $table->unique(['participant_id', 'attempt']);
        });

        Schema::create('eduquiz_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('eduquiz_session_participants')->cascadeOnDelete();
            $table->unsignedSmallInteger('position');
            $table->json('choices');
            $table->boolean('correct');
            $table->unsignedInteger('elapsed_seconds');
            $table->timestamp('answered_at');
            $table->unique(['attempt_id', 'position']);
        });

        Schema::create('eduquiz_results', function (Blueprint $table) {
            $table->foreignId('attempt_id')->primary()->constrained('eduquiz_session_participants')->cascadeOnDelete();
            $table->unsignedSmallInteger('score');
            $table->unsignedSmallInteger('total');
            $table->unsignedInteger('elapsed_seconds');
            $table->timestamp('created_at');
        });
    }

    public function down(): void
    {
        foreach ([
            'results', 'answers', 'session_participants', 'invitations', 'participants',
            'sessions', 'quiz_questions', 'quizzes', 'question_revisions', 'options',
            'questions', 'settings', 'instructors',
        ] as $table) {
            Schema::dropIfExists('eduquiz_'.$table);
        }
    }
};

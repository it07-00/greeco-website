<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        foreach (['eduquiz_questions', 'eduquiz_quizzes'] as $table) {
            Schema::table($table, fn (Blueprint $table) => $table->softDeletes());
        }
    }

    public function down(): void
    {
        foreach (['eduquiz_questions', 'eduquiz_quizzes'] as $table) {
            Schema::table($table, fn (Blueprint $table) => $table->dropSoftDeletes());
        }
    }
};

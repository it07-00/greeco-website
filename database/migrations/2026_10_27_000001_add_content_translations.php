<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private array $tables = ['pages', 'posts', 'services', 'projects', 'courses', 'service_categories', 'post_categories', 'course_categories', 'team_members', 'galleries', 'documents'];

    public function up(): void
    {
        foreach ($this->tables as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->json('translations')->nullable());
        }
        Schema::table('settings', fn (Blueprint $table) => $table->text('value_en')->nullable());
    }

    public function down(): void
    {
        Schema::table('settings', fn (Blueprint $table) => $table->dropColumn('value_en'));
        foreach ($this->tables as $name) {
            Schema::table($name, fn (Blueprint $table) => $table->dropColumn('translations'));
        }
    }
};

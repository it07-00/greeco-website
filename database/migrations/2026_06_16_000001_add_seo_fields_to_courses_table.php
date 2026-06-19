<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->string('meta_title', 500)->nullable()->after('image');
            $table->text('meta_description')->nullable()->after('meta_title');
            $table->string('meta_keywords', 500)->nullable()->after('meta_description');
            $table->string('canonical_url', 500)->nullable()->after('meta_keywords');
            $table->string('og_image', 500)->nullable()->after('canonical_url');
            $table->boolean('is_indexable')->default(true)->after('curriculum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('courses', function (Blueprint $table) {
            $table->dropColumn([
                'meta_title',
                'meta_description',
                'meta_keywords',
                'canonical_url',
                'og_image',
                'is_indexable',
            ]);
        });
    }
};

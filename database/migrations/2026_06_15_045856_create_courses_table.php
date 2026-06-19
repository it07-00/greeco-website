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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();
            $table->string('title', 500);
            $table->string('slug', 500)->unique();
            $table->text('description')->nullable();
            $table->longText('content')->nullable();
            $table->string('image')->nullable();
            $table->string('tag')->nullable();
            $table->string('badge_color')->nullable();
            $table->decimal('rating', 2, 1)->default(5.0);
            $table->unsignedInteger('rating_count')->default(0);
            $table->string('price');
            $table->string('original_price')->nullable();
            $table->string('discount')->nullable();
            
            // Instructor details
            $table->string('instructor_name')->nullable();
            $table->string('instructor_role')->nullable();
            $table->string('instructor_image')->nullable();
            $table->text('instructor_description')->nullable();
            
            // JSON curriculum structure (chapters & lessons)
            $table->json('curriculum')->nullable();
            
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

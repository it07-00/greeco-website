<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('registration_attendees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_registration_id')
                ->constrained()
                ->cascadeOnDelete();
            $table->string('full_name');
            $table->string('job_title')->nullable();
            $table->string('email');
            $table->string('phone', 50);
            $table->timestamps();

            $table->index('email');
            $table->index('phone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('registration_attendees');
    }
};

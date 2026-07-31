<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('course_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->string('code', 32)->unique();
            $table->string('company_name');
            $table->text('company_address');
            $table->string('tax_code', 50);
            $table->string('contact_name');
            $table->string('contact_email');
            $table->string('contact_phone', 50);
            $table->unsignedSmallInteger('attendee_count');
            $table->string('invoice_email');
            $table->text('consultation_question')->nullable();
            $table->string('source', 30)->default('website');
            $table->string('status', 30)->default('new');
            $table->boolean('is_read')->default(false);
            $table->text('admin_notes')->nullable();
            $table->timestamp('consent_at');
            $table->timestamps();

            $table->index(['course_id', 'status']);
            $table->index(['created_at', 'source']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_registrations');
    }
};

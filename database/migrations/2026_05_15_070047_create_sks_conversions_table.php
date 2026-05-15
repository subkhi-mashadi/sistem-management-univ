<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sks_conversions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mbkm_enrollment_id')->constrained('mbkm_enrollments');
            $table->foreignId('course_id')->constrained('courses');
            $table->unsignedTinyInteger('sks_converted');
            $table->string('letter_grade', 2)->nullable();
            $table->decimal('grade_point', 3, 2)->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sks_conversions');
    }
};

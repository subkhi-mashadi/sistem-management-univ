<?php

use App\Enums\Krs\AcademicStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transcripts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('semester_id')->constrained('semesters');
            $table->unsignedSmallInteger('sks_attempted')->default(0);
            $table->unsignedSmallInteger('sks_acquired')->default(0);
            $table->decimal('semester_gpa', 3, 2)->default(0);
            $table->decimal('cumulative_gpa', 3, 2)->default(0);
            $table->unsignedSmallInteger('sks_cumulative')->default(0);
            $table->enum('academic_status', AcademicStatus::values())->default('Normal');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transcripts');
    }
};

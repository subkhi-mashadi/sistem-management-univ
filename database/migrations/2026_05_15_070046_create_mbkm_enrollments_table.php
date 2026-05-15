<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Thesis\MbkmEnrollmentStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mbkm_enrollments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mbkm_program_id')->constrained('mbkm_programs');
            $table->foreignId('student_id')->constrained();
            $table->foreignId('semester_id')->constrained('semesters');
            $table->enum('status', MbkmEnrollmentStatus::values())->default('Registered');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->string('report_url')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mbkm_enrollments');
    }
};

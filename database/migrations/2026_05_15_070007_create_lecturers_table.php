<?php

use App\Enums\Academic\EducationLevel;
use App\Enums\Academic\EmploymentStatus;
use App\Enums\Academic\FunctionalPosition;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nidn')->nullable()->unique();
            $table->string('nidk')->nullable()->unique();
            $table->string('nip')->nullable()->unique();
            $table->foreignId('study_program_id')->nullable()->constrained('study_programs')->nullOnDelete();
            $table->enum('functional_position', FunctionalPosition::values())->nullable();
            $table->string('structural_position')->nullable();
            $table->enum('education_level', EducationLevel::values())->nullable();
            $table->json('expertise_keywords')->nullable();
            $table->enum('employment_status', EmploymentStatus::values())->nullable();
            $table->date('start_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturers');
    }
};

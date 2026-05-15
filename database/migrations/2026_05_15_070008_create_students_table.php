<?php

use App\Enums\Academic\EntryPath;
use App\Enums\Academic\StudentStatus;
use App\Enums\Common\Gender;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained('users')->cascadeOnDelete();
            $table->string('nim')->unique();
            $table->foreignId('study_program_id')->constrained('study_programs')->restrictOnDelete();
            $table->foreignId('curriculum_id')->constrained('curriculums')->restrictOnDelete();
            $table->year('enrollment_year');
            $table->foreignId('academic_advisor_id')->nullable()->constrained('lecturers')->nullOnDelete();
            $table->enum('status', StudentStatus::values())->default('Aktif');
            $table->enum('entry_path', EntryPath::values())->nullable();
            $table->unsignedTinyInteger('ukt_group')->nullable();
            $table->enum('gender', Gender::values())->nullable();
            $table->string('birth_place')->nullable();
            $table->date('birth_date')->nullable();
            $table->text('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('parent_name')->nullable();
            $table->string('parent_phone')->nullable();
            $table->string('religion')->nullable();
            $table->string('nationality')->nullable()->default('Indonesia');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->index(['study_program_id', 'status', 'enrollment_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};

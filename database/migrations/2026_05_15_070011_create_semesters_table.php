<?php

use App\Enums\Academic\SemesterTerm;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semesters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_calendar_id')->constrained('academic_calendars')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('term', SemesterTerm::values());
            $table->date('start_date');
            $table->date('end_date');
            $table->date('krs_start')->nullable();
            $table->date('krs_end')->nullable();
            $table->date('lecture_start')->nullable();
            $table->date('lecture_end')->nullable();
            $table->date('uts_start')->nullable();
            $table->date('uts_end')->nullable();
            $table->date('uas_start')->nullable();
            $table->date('uas_end')->nullable();
            $table->date('grade_input_deadline')->nullable();
            $table->boolean('is_active')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semesters');
    }
};

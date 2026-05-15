<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lecturer_workloads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('lecturers')->cascadeOnDelete();
            $table->foreignId('semester_id')->constrained('semesters')->cascadeOnDelete();
            $table->decimal('teaching_sks', 5, 2)->default(0);
            $table->decimal('advisory_sks', 5, 2)->default(0);
            $table->decimal('research_sks', 5, 2)->default(0);
            $table->decimal('service_sks', 5, 2)->default(0);
            $table->decimal('additional_sks', 5, 2)->default(0);
            $table->decimal('total_sks', 5, 2)->default(0);
            $table->boolean('is_locked')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['lecturer_id', 'semester_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lecturer_workloads');
    }
};

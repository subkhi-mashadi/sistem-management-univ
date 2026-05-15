<?php

use App\Enums\Scheduling\ClassSessionStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('class_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained('schedules')->cascadeOnDelete();
            $table->unsignedTinyInteger('meeting_number');
            $table->date('session_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('topic')->nullable();
            $table->text('material_summary')->nullable();
            $table->enum('status', ClassSessionStatus::values())->default('Scheduled');
            $table->foreignId('substitute_lecturer_id')->nullable()->constrained('lecturers')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('class_sessions');
    }
};

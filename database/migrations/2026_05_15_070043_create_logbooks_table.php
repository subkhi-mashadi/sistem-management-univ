<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logbooks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_topic_id')->constrained('thesis_topics');
            $table->foreignId('student_id')->constrained();
            $table->foreignId('lecturer_id')->constrained('lecturers');
            $table->date('session_date');
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->text('topic_discussed');
            $table->text('progress_summary')->nullable();
            $table->text('lecturer_feedback')->nullable();
            $table->boolean('is_verified')->default(false);
            $table->timestamp('verified_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logbooks');
    }
};

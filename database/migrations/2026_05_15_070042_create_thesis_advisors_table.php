<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Thesis\ThesisAdvisorStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis_advisors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_topic_id')->constrained('thesis_topics');
            $table->foreignId('lecturer_id')->constrained('lecturers');
            $table->unsignedTinyInteger('advisor_order');
            $table->timestamp('assigned_at');
            $table->enum('status', ThesisAdvisorStatus::values())->default('Active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->unique(['thesis_topic_id', 'lecturer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_advisors');
    }
};

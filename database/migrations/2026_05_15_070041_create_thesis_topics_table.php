<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Thesis\ThesisTopicStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis_topics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->text('abstract')->nullable();
            $table->json('keywords')->nullable();
            $table->string('research_field')->nullable();
            $table->decimal('similarity_score', 5, 2)->nullable();
            $table->enum('status', ThesisTopicStatus::values())->default('Draft');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->fullText(['title', 'abstract']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_topics');
    }
};

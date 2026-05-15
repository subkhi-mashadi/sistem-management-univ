<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Thesis\ResearchCategory;
use App\Enums\Thesis\ResearchReviewStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('research_repository', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->foreignId('lecturer_id')->nullable()->constrained('lecturers')->nullOnDelete();
            $table->string('title');
            $table->longText('abstract')->nullable();
            $table->json('keywords')->nullable();
            $table->enum('category', ResearchCategory::values());
            $table->year('publish_year')->nullable();
            $table->string('doi')->nullable();
            $table->string('file_url')->nullable();
            $table->boolean('is_published')->default(false);
            $table->enum('review_status', ResearchReviewStatus::values())->default('Pending Review');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->fullText(['title', 'abstract']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('research_repository');
    }
};

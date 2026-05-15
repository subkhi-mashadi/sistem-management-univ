<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Thesis\DefenseStatus;
use App\Enums\Thesis\DefenseType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('thesis_defenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thesis_topic_id')->constrained('thesis_topics');
            $table->enum('defense_type', DefenseType::values());
            $table->timestamp('scheduled_at');
            $table->string('room')->nullable();
            $table->json('examiner_ids');
            $table->decimal('plagiarism_score', 5, 2)->nullable();
            $table->decimal('final_score', 5, 2)->nullable();
            $table->string('letter_grade', 2)->nullable();
            $table->enum('status', DefenseStatus::values())->default('Scheduled');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('thesis_defenses');
    }
};

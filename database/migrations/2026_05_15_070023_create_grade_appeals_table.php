<?php

use App\Enums\Krs\AppealStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('grade_appeals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('grade_id')->constrained('grades');
            $table->foreignId('student_id')->constrained('students');
            $table->text('reason');
            $table->string('original_letter', 2);
            $table->string('revised_letter', 2)->nullable();
            $table->decimal('original_score', 5, 2)->nullable();
            $table->decimal('revised_score', 5, 2)->nullable();
            $table->enum('status', AppealStatus::values())->default('Pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('reviewed_at')->nullable();
            $table->text('review_notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_appeals');
    }
};

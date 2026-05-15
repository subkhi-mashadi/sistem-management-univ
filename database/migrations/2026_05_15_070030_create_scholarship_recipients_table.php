<?php

use App\Enums\Finance\ScholarshipRecipientStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scholarship_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scholarship_id')->constrained('scholarships');
            $table->foreignId('student_id')->constrained('students');
            $table->foreignId('semester_id')->nullable()->constrained('semesters')->nullOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->enum('status', ScholarshipRecipientStatus::values())->default('Active');
            $table->decimal('granted_amount', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['scholarship_id', 'student_id', 'semester_id'], 'scholarship_recipients_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scholarship_recipients');
    }
};

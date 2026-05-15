<?php

use App\Enums\Hris\TeachingHonorStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teaching_honors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lecturer_id')->constrained('lecturers');
            $table->foreignId('class_session_id')->nullable()->constrained('class_sessions');
            $table->foreignId('payroll_period_id')->nullable()->constrained('payroll_periods');
            $table->decimal('rate_per_sks', 12, 2)->default(0);
            $table->decimal('sks', 5, 2)->default(0);
            $table->unsignedTinyInteger('meeting_count')->default(1);
            $table->decimal('amount', 12, 2);
            $table->enum('status', TeachingHonorStatus::values())->default('Pending');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['lecturer_id', 'payroll_period_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teaching_honors');
    }
};

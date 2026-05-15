<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\EOffice\LetterRequestStatus;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_requests', function (Blueprint $table) {
            $table->id();
            $table->string('letter_number')->nullable()->unique();
            $table->foreignId('letter_template_id')->constrained('letter_templates');
            $table->foreignId('workflow_id')->nullable()->constrained('workflows');
            $table->foreignId('requester_id')->constrained('users');
            $table->foreignId('student_id')->nullable()->constrained('students')->nullOnDelete();
            $table->json('form_data');
            $table->foreignId('current_step_id')->nullable()->constrained('workflow_steps')->nullOnDelete();
            $table->enum('status', LetterRequestStatus::values())->default('Draft');
            $table->string('pdf_url')->nullable();
            $table->string('qr_code')->nullable()->unique();
            $table->date('qr_valid_until')->nullable();
            $table->timestamp('issued_at')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_requests');
    }
};

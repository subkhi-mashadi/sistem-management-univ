<?php

use App\Enums\Finance\PaymentMethod;
use App\Enums\Finance\PaymentStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payment_number')->unique();
            $table->foreignId('invoice_id')->constrained('invoices');
            $table->foreignId('student_id')->constrained('students');
            $table->decimal('amount', 12, 2);
            $table->enum('payment_method', PaymentMethod::values());
            $table->string('bank_code')->nullable();
            $table->string('bank_reference')->nullable()->index();
            $table->timestamp('payment_date');
            $table->enum('status', PaymentStatus::values())->default('Pending');
            $table->timestamp('reconciled_at')->nullable();
            $table->json('raw_payload')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index('payment_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};

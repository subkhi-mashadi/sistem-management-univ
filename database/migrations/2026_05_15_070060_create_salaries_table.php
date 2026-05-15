<?php

use App\Enums\Hris\SalaryStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('salaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained('payroll_periods');
            $table->foreignId('employee_id')->constrained('employees');
            $table->decimal('base_salary', 12, 2)->default(0);
            $table->decimal('total_allowance', 12, 2)->default(0);
            $table->decimal('teaching_honor', 12, 2)->default(0);
            $table->decimal('other_income', 12, 2)->default(0);
            $table->decimal('gross_total', 12, 2)->default(0);
            $table->decimal('bpjs_deduction', 12, 2)->default(0);
            $table->decimal('tax_pph21', 12, 2)->default(0);
            $table->decimal('other_deduction', 12, 2)->default(0);
            $table->decimal('total_deduction', 12, 2)->default(0);
            $table->decimal('net_total', 12, 2)->default(0);
            $table->enum('status', SalaryStatus::values())->default('Draft');
            $table->timestamp('paid_at')->nullable();
            $table->string('slip_pdf_url')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['payroll_period_id', 'employee_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('salaries');
    }
};

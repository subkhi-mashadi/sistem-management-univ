<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tax_calculations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_period_id')->constrained('payroll_periods');
            $table->foreignId('employee_id')->constrained('employees');
            $table->year('tax_year');
            $table->decimal('gross_year_to_date', 14, 2)->default(0);
            $table->string('ptkp_category')->nullable();
            $table->decimal('ptkp_amount', 12, 2)->default(0);
            $table->decimal('taxable_income', 14, 2)->default(0);
            $table->decimal('tax_pph21', 12, 2)->default(0);
            $table->decimal('tax_pph21_ytd', 14, 2)->default(0);
            $table->boolean('is_final')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tax_calculations');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\Thesis\MbkmType;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mbkm_programs', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('type', MbkmType::values());
            $table->string('partner_name')->nullable();
            $table->text('partner_address')->nullable();
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('total_sks');
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mbkm_programs');
    }
};

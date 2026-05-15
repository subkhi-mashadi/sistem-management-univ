<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('curriculums', function (Blueprint $table) {
            $table->id();
            $table->foreignId('study_program_id')->constrained('study_programs')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('version');
            $table->year('effective_year');
            $table->unsignedSmallInteger('total_sks_required');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_archived')->default(false);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['study_program_id', 'code', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('curriculums');
    }
};

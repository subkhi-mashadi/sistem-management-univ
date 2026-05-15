<?php

use App\Enums\Academic\CourseType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('curriculum_id')->constrained('curriculums')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('code');
            $table->string('name');
            $table->string('name_en')->nullable();
            $table->unsignedTinyInteger('sks_theory')->default(0);
            $table->unsignedTinyInteger('sks_practice')->default(0);
            $table->unsignedTinyInteger('sks_field')->default(0);
            $table->unsignedTinyInteger('total_sks')->default(0);
            $table->unsignedTinyInteger('semester');
            $table->enum('course_type', CourseType::values());
            $table->text('description')->nullable();
            $table->json('learning_outcomes')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['curriculum_id', 'code']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};

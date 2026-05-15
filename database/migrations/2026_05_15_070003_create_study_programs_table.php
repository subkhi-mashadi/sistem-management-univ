<?php

use App\Enums\Academic\Accreditation;
use App\Enums\Academic\DegreeLevel;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('faculty_id')->constrained('faculties')->cascadeOnUpdate()->restrictOnDelete();
            $table->string('code')->unique();
            $table->string('name');
            $table->enum('degree_level', DegreeLevel::values());
            $table->string('pddikti_code')->nullable()->index();
            $table->enum('accreditation', Accreditation::values())->nullable();
            $table->date('accreditation_valid_until')->nullable();
            $table->foreignId('head_id')->nullable()->constrained('users')->nullOnDelete();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('study_programs');
    }
};

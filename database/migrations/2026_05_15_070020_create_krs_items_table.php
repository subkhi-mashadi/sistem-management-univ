<?php

use App\Enums\Krs\KrsItemStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('krs_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('course_offering_id')->constrained('course_offerings');
            $table->enum('status', KrsItemStatus::values())->default('Active');
            $table->timestamp('dropped_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->unique(['enrollment_id', 'course_offering_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('krs_items');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('scholarship_recipients', function (Blueprint $table) {
            $table->index('scholarship_id');
            $table->index('student_id');
            $table->index('semester_id');
            $table->dropUnique('scholarship_recipients_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scholarship_recipients', function (Blueprint $table) {
            $table->unique(['scholarship_id', 'student_id', 'semester_id'], 'scholarship_recipients_unique');
            $table->dropIndex(['scholarship_id']);
            $table->dropIndex(['student_id']);
            $table->dropIndex(['semester_id']);
        });
    }
};

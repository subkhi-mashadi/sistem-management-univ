<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_sessions', function (Blueprint $table) {
            $table->foreignId('classroom_id')->nullable()->constrained('classrooms')->nullOnDelete()->after('schedule_id');
            $table->string('attendance_token', 8)->nullable()->unique()->after('notes');
            $table->timestamp('token_expires_at')->nullable()->after('attendance_token');
        });

        DB::statement("ALTER TABLE attendances MODIFY COLUMN check_method ENUM('QR','Fingerprint','Face','Manual','Token') NULL");
    }

    public function down(): void
    {
        Schema::table('class_sessions', function (Blueprint $table) {
            $table->dropForeign(['classroom_id']);
            $table->dropColumn(['classroom_id', 'attendance_token', 'token_expires_at']);
        });

        DB::statement("ALTER TABLE attendances MODIFY COLUMN check_method ENUM('QR','Fingerprint','Face','Manual') NULL");
    }
};

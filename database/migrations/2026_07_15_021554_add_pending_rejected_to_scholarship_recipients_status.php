<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("ALTER TABLE scholarship_recipients MODIFY status ENUM('Pending','Active','Rejected','Suspended','Completed','Revoked') NOT NULL DEFAULT 'Active'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("UPDATE scholarship_recipients SET status = 'Active' WHERE status IN ('Pending','Rejected')");
        DB::statement("ALTER TABLE scholarship_recipients MODIFY status ENUM('Active','Suspended','Completed','Revoked') NOT NULL DEFAULT 'Active'");
    }
};

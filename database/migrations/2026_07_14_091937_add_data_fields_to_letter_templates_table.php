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
        Schema::table('letter_templates', function (Blueprint $table) {
            $table->text('closing_text')->nullable()->after('body');
            $table->json('data_fields')->nullable()->after('closing_text');
            $table->json('custom_fields')->nullable()->after('data_fields');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('letter_templates', function (Blueprint $table) {
            $table->dropColumn(['closing_text', 'data_fields', 'custom_fields']);
        });
    }
};

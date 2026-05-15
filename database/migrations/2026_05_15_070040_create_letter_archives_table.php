<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('letter_archives', function (Blueprint $table) {
            $table->id();
            $table->foreignId('letter_request_id')->nullable()->constrained('letter_requests')->nullOnDelete();
            $table->string('letter_number')->index();
            $table->string('category');
            $table->string('subject');
            $table->string('pdf_url');
            $table->longText('searchable_text')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('archived_at');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->fullText('searchable_text');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('letter_archives');
    }
};

<?php

use App\Enums\Rbac\UserType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->unique()->after('id');
            $table->string('full_name')->nullable()->after('name');
            $table->enum('user_type', UserType::values())->nullable()->after('full_name');
            $table->string('external_id')->nullable()->index()->after('user_type');
            $table->boolean('is_active')->default(true)->after('external_id');
            $table->boolean('mfa_enabled')->default(false)->after('is_active');
            $table->string('mfa_secret')->nullable()->after('mfa_enabled');
            $table->timestamp('last_login_at')->nullable()->after('mfa_secret');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->timestamp('password_expires_at')->nullable()->after('last_login_ip');
            $table->unsignedTinyInteger('failed_login_attempts')->default(0)->after('password_expires_at');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');
            $table->string('avatar')->nullable()->after('locked_until');
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn([
                'username', 'full_name', 'user_type', 'external_id', 'is_active',
                'mfa_enabled', 'mfa_secret', 'last_login_at', 'last_login_ip',
                'password_expires_at', 'failed_login_attempts', 'locked_until', 'avatar',
            ]);
        });
    }
};

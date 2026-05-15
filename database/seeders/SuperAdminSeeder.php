<?php

namespace Database\Seeders;

use App\Enums\Rbac\Role as RoleEnum;
use App\Enums\Rbac\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'superadmin@univercity.test'],
            [
                'name' => 'Super Admin',
                'full_name' => 'Super Administrator',
                'username' => 'superadmin',
                'user_type' => UserType::Admin,
                'password' => Hash::make('password'),
                'is_active' => true,
                'mfa_enabled' => false,
                'email_verified_at' => now(),
            ],
        );

        if (! $user->hasRole(RoleEnum::SuperAdmin->value)) {
            $user->assignRole(RoleEnum::SuperAdmin->value);
        }
    }
}

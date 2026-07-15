<?php

namespace Database\Seeders;

use App\Enums\Rbac\Role as RoleEnum;
use App\Enums\Rbac\UserType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

/**
 * Demo users untuk semua role staff yang belum punya akun.
 * Password semua: password123
 *
 * Cara pakai: php artisan db:seed --class=DemoStaffSeeder
 */
class DemoStaffSeeder extends Seeder
{
    private const PASSWORD = 'password123';

    /** @var array<int, array{email: string, name: string, full_name: string, user_type: UserType, roles: RoleEnum[]}> */
    private array $staff = [];

    public function run(): void
    {
        $this->staff = [
            [
                'email'     => 'rektor@univercity.test',
                'username'  => 'rektor',
                'name'      => 'rektor',
                'full_name' => 'Prof. Dr. Ahmad Rektor, M.Pd.',
                'user_type' => UserType::Staff,
                'roles'     => [RoleEnum::Rektor],
            ],
            [
                'email'     => 'wakilrektor@univercity.test',
                'username'  => 'wakilrektor',
                'name'      => 'wakilrektor',
                'full_name' => 'Dr. Siti Wakil Rektor, M.Si.',
                'user_type' => UserType::Staff,
                'roles'     => [RoleEnum::WakilRektor],
            ],
            [
                'email'     => 'adminakademik@univercity.test',
                'username'  => 'adminakademik',
                'name'      => 'adminakademik',
                'full_name' => 'Budi Admin Akademik',
                'user_type' => UserType::Staff,
                'roles'     => [RoleEnum::AdminAkademik],
            ],
            [
                'email'     => 'adminkeuangan@univercity.test',
                'username'  => 'adminkeuangan',
                'name'      => 'adminkeuangan',
                'full_name' => 'Dewi Admin Keuangan',
                'user_type' => UserType::Staff,
                'roles'     => [RoleEnum::AdminKeuangan],
            ],
            [
                'email'     => 'adminsdm@univercity.test',
                'username'  => 'adminsdm',
                'name'      => 'adminsdm',
                'full_name' => 'Rudi Admin SDM',
                'user_type' => UserType::Staff,
                'roles'     => [RoleEnum::AdminSdm],
            ],
            [
                'email'     => 'itadmin@univercity.test',
                'username'  => 'itadmin',
                'name'      => 'itadmin',
                'full_name' => 'Eko IT Admin',
                'user_type' => UserType::Admin,
                'roles'     => [RoleEnum::ItAdmin],
            ],
        ];

        foreach ($this->staff as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'username'   => $data['username'],
                    'name'       => $data['name'],
                    'full_name'  => $data['full_name'],
                    'user_type'  => $data['user_type'],
                    'is_active'  => true,
                    'password'   => Hash::make(self::PASSWORD),
                ],
            );

            $roleNames = array_map(fn (RoleEnum $r) => $r->value, $data['roles']);
            $user->syncRoles($roleNames);

            $this->command->info("✓ {$user->email} → ".implode(', ', $roleNames));
        }

        $this->command->newLine();
        $this->command->info('Password semua staff: '.self::PASSWORD);
    }
}

<?php

namespace App\Filament\Admin\Resources\Students\Pages;

use App\Enums\Rbac\Role as RoleEnum;
use App\Enums\Rbac\UserType;
use App\Filament\Admin\Resources\Students\StudentResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateStudent extends CreateRecord
{
    protected static string $resource = StudentResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $nim = $data['nim'];
            $email = $data['email'] ?? "{$nim}@student.univercity.test";
            $password = $data['password'] ?? $nim;

            $user = User::create([
                'name' => $data['full_name'],
                'full_name' => $data['full_name'],
                'email' => $email,
                'username' => $nim,
                'password' => Hash::make($password),
                'user_type' => UserType::Student,
                'external_id' => $nim,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $user->assignRole(RoleEnum::Mahasiswa->value);

            unset($data['full_name'], $data['email'], $data['password']);
            $data['user_id'] = $user->id;

            return static::getModel()::create($data);
        });
    }
}

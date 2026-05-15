<?php

namespace App\Filament\Admin\Resources\Lecturers\Pages;

use App\Enums\Academic\StructuralPosition;
use App\Enums\Rbac\Role as RoleEnum;
use App\Enums\Rbac\UserType;
use App\Filament\Admin\Resources\Lecturers\LecturerResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateLecturer extends CreateRecord
{
    protected static string $resource = LecturerResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['full_name'],
                'full_name' => $data['full_name'],
                'email' => $data['email'],
                'username' => $data['email'],
                'password' => Hash::make($data['password']),
                'user_type' => UserType::Lecturer,
                'external_id' => $data['nidn'] ?? $data['nip'] ?? null,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $user->assignRole(RoleEnum::Dosen->value);

            $structural = $data['structural_position'] ?? null;
            if ($structural === StructuralPosition::Dekan->value) {
                $user->assignRole(RoleEnum::Dekan->value);
            } elseif ($structural === StructuralPosition::Kaprodi->value) {
                $user->assignRole(RoleEnum::Kaprodi->value);
            } elseif (in_array($structural, [
                StructuralPosition::WakilDekan1->value,
                StructuralPosition::WakilDekan2->value,
                StructuralPosition::WakilDekan3->value,
            ], true)) {
                $user->assignRole(RoleEnum::WakilDekan->value);
            }

            unset($data['full_name'], $data['email'], $data['password'], $data['phone']);
            $data['user_id'] = $user->id;

            return static::getModel()::create($data);
        });
    }
}

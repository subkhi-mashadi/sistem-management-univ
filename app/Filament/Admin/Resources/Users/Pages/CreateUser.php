<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\User;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['name'] = $data['full_name'];

        if (empty($data['username'])) {
            $data['username'] = Str::before($data['email'], '@').'-'.Str::random(4);
        }

        $roleNames = Role::whereIn('id', $data['roles'] ?? [])->pluck('name')->all();
        $data['user_type'] = User::deriveUserType($roleNames)?->value;

        return $data;
    }
}

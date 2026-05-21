<?php

namespace App\Filament\Admin\Resources\Users\Pages;

use App\Filament\Admin\Resources\Users\UserResource;
use App\Models\User;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;
use Spatie\Permission\Models\Role;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (! empty($data['full_name'])) {
            $data['name'] = $data['full_name'];
        }

        $roleNames = Role::whereIn('id', $data['roles'] ?? [])->pluck('name')->all();
        $derived = User::deriveUserType($roleNames);
        if ($derived !== null) {
            $data['user_type'] = $derived->value;
        }

        return $data;
    }
}

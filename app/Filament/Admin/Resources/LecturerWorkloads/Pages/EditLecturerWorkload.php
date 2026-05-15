<?php

namespace App\Filament\Admin\Resources\LecturerWorkloads\Pages;

use App\Filament\Admin\Resources\LecturerWorkloads\LecturerWorkloadResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLecturerWorkload extends EditRecord
{
    protected static string $resource = LecturerWorkloadResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

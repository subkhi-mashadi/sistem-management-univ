<?php

namespace App\Filament\Admin\Resources\TeachingHonors\Pages;

use App\Filament\Admin\Resources\TeachingHonors\TeachingHonorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTeachingHonor extends EditRecord
{
    protected static string $resource = TeachingHonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

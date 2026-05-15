<?php

namespace App\Filament\Admin\Resources\MbkmEnrollments\Pages;

use App\Filament\Admin\Resources\MbkmEnrollments\MbkmEnrollmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditMbkmEnrollment extends EditRecord
{
    protected static string $resource = MbkmEnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

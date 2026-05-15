<?php

namespace App\Filament\Admin\Resources\GradeAppeals\Pages;

use App\Filament\Admin\Resources\GradeAppeals\GradeAppealResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditGradeAppeal extends EditRecord
{
    protected static string $resource = GradeAppealResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

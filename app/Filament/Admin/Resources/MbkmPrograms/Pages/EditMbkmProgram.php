<?php

namespace App\Filament\Admin\Resources\MbkmPrograms\Pages;

use App\Filament\Admin\Resources\MbkmPrograms\MbkmProgramResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditMbkmProgram extends EditRecord
{
    protected static string $resource = MbkmProgramResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

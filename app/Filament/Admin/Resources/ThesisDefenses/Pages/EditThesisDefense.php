<?php

namespace App\Filament\Admin\Resources\ThesisDefenses\Pages;

use App\Filament\Admin\Resources\ThesisDefenses\ThesisDefenseResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditThesisDefense extends EditRecord
{
    protected static string $resource = ThesisDefenseResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}

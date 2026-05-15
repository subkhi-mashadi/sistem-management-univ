<?php

namespace App\Filament\Admin\Resources\KrsItems\Pages;

use App\Filament\Admin\Resources\KrsItems\KrsItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKrsItem extends EditRecord
{
    protected static string $resource = KrsItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

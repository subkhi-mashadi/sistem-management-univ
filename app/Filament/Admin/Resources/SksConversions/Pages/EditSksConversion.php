<?php

namespace App\Filament\Admin\Resources\SksConversions\Pages;

use App\Filament\Admin\Resources\SksConversions\SksConversionResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSksConversion extends EditRecord
{
    protected static string $resource = SksConversionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

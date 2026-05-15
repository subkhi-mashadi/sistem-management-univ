<?php

namespace App\Filament\Admin\Resources\Logbooks\Pages;

use App\Filament\Admin\Resources\Logbooks\LogbookResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditLogbook extends EditRecord
{
    protected static string $resource = LogbookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

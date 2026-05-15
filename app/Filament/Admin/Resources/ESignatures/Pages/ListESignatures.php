<?php

namespace App\Filament\Admin\Resources\ESignatures\Pages;

use App\Filament\Admin\Resources\ESignatures\ESignatureResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListESignatures extends ListRecords
{
    protected static string $resource = ESignatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

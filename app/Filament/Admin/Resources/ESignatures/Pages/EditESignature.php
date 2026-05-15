<?php

namespace App\Filament\Admin\Resources\ESignatures\Pages;

use App\Filament\Admin\Resources\ESignatures\ESignatureResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditESignature extends EditRecord
{
    protected static string $resource = ESignatureResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

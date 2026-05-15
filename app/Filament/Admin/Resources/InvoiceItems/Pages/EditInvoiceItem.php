<?php

namespace App\Filament\Admin\Resources\InvoiceItems\Pages;

use App\Filament\Admin\Resources\InvoiceItems\InvoiceItemResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditInvoiceItem extends EditRecord
{
    protected static string $resource = InvoiceItemResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

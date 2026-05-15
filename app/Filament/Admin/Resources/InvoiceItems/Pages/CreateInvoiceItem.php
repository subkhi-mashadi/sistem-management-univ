<?php

namespace App\Filament\Admin\Resources\InvoiceItems\Pages;

use App\Filament\Admin\Resources\InvoiceItems\InvoiceItemResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoiceItem extends CreateRecord
{
    protected static string $resource = InvoiceItemResource::class;
}

<?php

namespace App\Filament\Admin\Resources\Logbooks\Pages;

use App\Filament\Admin\Resources\Logbooks\LogbookResource;
use Filament\Resources\Pages\CreateRecord;

class CreateLogbook extends CreateRecord
{
    protected static string $resource = LogbookResource::class;
}

<?php

namespace App\Filament\Lecturer\Resources\Logbooks\Pages;

use App\Filament\Lecturer\Resources\Logbooks\LogbookResource;
use Filament\Resources\Pages\ListRecords;

class ListLogbooks extends ListRecords
{
    protected static string $resource = LogbookResource::class;
}

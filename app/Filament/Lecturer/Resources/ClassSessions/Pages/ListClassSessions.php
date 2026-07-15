<?php

namespace App\Filament\Lecturer\Resources\ClassSessions\Pages;

use App\Filament\Lecturer\Resources\ClassSessions\ClassSessionResource;
use Filament\Resources\Pages\ListRecords;

class ListClassSessions extends ListRecords
{
    protected static string $resource = ClassSessionResource::class;
}

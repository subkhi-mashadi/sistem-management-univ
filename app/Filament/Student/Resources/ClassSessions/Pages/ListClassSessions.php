<?php

namespace App\Filament\Student\Resources\ClassSessions\Pages;

use App\Filament\Student\Resources\ClassSessions\ClassSessionResource;
use Filament\Resources\Pages\ListRecords;

class ListClassSessions extends ListRecords
{
    protected static string $resource = ClassSessionResource::class;
}

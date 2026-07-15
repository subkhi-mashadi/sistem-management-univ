<?php

namespace App\Filament\Lecturer\Resources\ThesisAdvisees\Pages;

use App\Filament\Lecturer\Resources\ThesisAdvisees\ThesisAdviseeResource;
use Filament\Resources\Pages\ListRecords;

class ListThesisAdvisees extends ListRecords
{
    protected static string $resource = ThesisAdviseeResource::class;
}

<?php

namespace App\Filament\Admin\Resources\ThesisAdvisors\Pages;

use App\Filament\Admin\Resources\ThesisAdvisors\ThesisAdvisorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListThesisAdvisors extends ListRecords
{
    protected static string $resource = ThesisAdvisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

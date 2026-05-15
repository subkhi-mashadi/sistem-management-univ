<?php

namespace App\Filament\Admin\Resources\Scholarships\Pages;

use App\Filament\Admin\Resources\Scholarships\ScholarshipResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListScholarships extends ListRecords
{
    protected static string $resource = ScholarshipResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

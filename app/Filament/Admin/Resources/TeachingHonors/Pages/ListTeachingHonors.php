<?php

namespace App\Filament\Admin\Resources\TeachingHonors\Pages;

use App\Filament\Admin\Resources\TeachingHonors\TeachingHonorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTeachingHonors extends ListRecords
{
    protected static string $resource = TeachingHonorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

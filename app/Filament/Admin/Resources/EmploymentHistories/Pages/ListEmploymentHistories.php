<?php

namespace App\Filament\Admin\Resources\EmploymentHistories\Pages;

use App\Filament\Admin\Resources\EmploymentHistories\EmploymentHistoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListEmploymentHistories extends ListRecords
{
    protected static string $resource = EmploymentHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources\SalaryDetails\Pages;

use App\Filament\Admin\Resources\SalaryDetails\SalaryDetailResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSalaryDetails extends ListRecords
{
    protected static string $resource = SalaryDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

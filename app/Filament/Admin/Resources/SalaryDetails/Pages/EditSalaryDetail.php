<?php

namespace App\Filament\Admin\Resources\SalaryDetails\Pages;

use App\Filament\Admin\Resources\SalaryDetails\SalaryDetailResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSalaryDetail extends EditRecord
{
    protected static string $resource = SalaryDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

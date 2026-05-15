<?php

namespace App\Filament\Admin\Resources\EmploymentHistories\Pages;

use App\Filament\Admin\Resources\EmploymentHistories\EmploymentHistoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditEmploymentHistory extends EditRecord
{
    protected static string $resource = EmploymentHistoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

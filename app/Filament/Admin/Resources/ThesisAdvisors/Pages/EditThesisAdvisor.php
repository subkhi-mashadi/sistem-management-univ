<?php

namespace App\Filament\Admin\Resources\ThesisAdvisors\Pages;

use App\Filament\Admin\Resources\ThesisAdvisors\ThesisAdvisorResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditThesisAdvisor extends EditRecord
{
    protected static string $resource = ThesisAdvisorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

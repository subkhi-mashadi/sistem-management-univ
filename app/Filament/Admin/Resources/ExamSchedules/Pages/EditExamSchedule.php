<?php

namespace App\Filament\Admin\Resources\ExamSchedules\Pages;

use App\Filament\Admin\Resources\ExamSchedules\ExamScheduleResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditExamSchedule extends EditRecord
{
    protected static string $resource = ExamScheduleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

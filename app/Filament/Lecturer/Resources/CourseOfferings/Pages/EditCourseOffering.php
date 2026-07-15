<?php

namespace App\Filament\Lecturer\Resources\CourseOfferings\Pages;

use App\Filament\Lecturer\Resources\CourseOfferings\CourseOfferingResource;
use Filament\Resources\Pages\EditRecord;

class EditCourseOffering extends EditRecord
{
    protected static string $resource = CourseOfferingResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}

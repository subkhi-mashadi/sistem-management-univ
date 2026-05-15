<?php

namespace App\Filament\Admin\Resources\CourseOfferings\Pages;

use App\Filament\Admin\Resources\CourseOfferings\CourseOfferingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCourseOffering extends CreateRecord
{
    protected static string $resource = CourseOfferingResource::class;
}

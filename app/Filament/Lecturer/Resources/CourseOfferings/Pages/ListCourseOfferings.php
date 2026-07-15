<?php

namespace App\Filament\Lecturer\Resources\CourseOfferings\Pages;

use App\Filament\Lecturer\Resources\CourseOfferings\CourseOfferingResource;
use Filament\Resources\Pages\ListRecords;

class ListCourseOfferings extends ListRecords
{
    protected static string $resource = CourseOfferingResource::class;
}

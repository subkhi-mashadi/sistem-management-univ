<?php

namespace App\Filament\Lecturer\Resources\Enrollments\Pages;

use App\Filament\Lecturer\Resources\Enrollments\EnrollmentResource;
use Filament\Resources\Pages\ListRecords;

class ListEnrollments extends ListRecords
{
    protected static string $resource = EnrollmentResource::class;
}

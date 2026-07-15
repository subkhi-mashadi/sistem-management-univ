<?php

namespace App\Filament\Student\Resources\Enrollments\Pages;

use App\Enums\Krs\EnrollmentStatus;
use App\Filament\Student\Resources\Enrollments\EnrollmentResource;
use Filament\Resources\Pages\CreateRecord;

class CreateEnrollment extends CreateRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['student_id'] = auth()->user()?->student?->id;
        $data['status'] = EnrollmentStatus::Draft->value;
        $data['max_sks'] ??= 24;
        $data['total_sks_taken'] ??= 0;

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('edit', ['record' => $this->record]);
    }
}

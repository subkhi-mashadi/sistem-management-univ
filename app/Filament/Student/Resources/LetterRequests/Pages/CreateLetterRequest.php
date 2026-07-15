<?php

namespace App\Filament\Student\Resources\LetterRequests\Pages;

use App\Enums\EOffice\LetterRequestStatus;
use App\Filament\Student\Resources\LetterRequests\LetterRequestResource;
use App\Models\LetterTemplate;
use App\Support\EOffice\LetterSystemFields;
use Filament\Resources\Pages\CreateRecord;

class CreateLetterRequest extends CreateRecord
{
    protected static string $resource = LetterRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $student = auth()->user()?->student;

        $data['requester_id'] = auth()->id();
        $data['student_id'] = $student?->id;
        $data['status'] = LetterRequestStatus::Draft;

        $template = LetterTemplate::find($data['letter_template_id'] ?? null);
        $autoFields = ($template && $student && $template->data_fields)
            ? LetterSystemFields::resolve($template->data_fields, $student)
            : [];

        // Field sistem (otomatis) digabung dengan field custom yang diisi mahasiswa;
        // custom menang kalau ada key yang sama (seharusnya tidak terjadi).
        $data['form_data'] = array_merge($autoFields, $data['form_data'] ?? []);

        return $data;
    }
}

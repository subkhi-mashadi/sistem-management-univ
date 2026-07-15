<?php

namespace App\Filament\Student\Resources\ScholarshipRecipients\Pages;

use App\Filament\Student\Resources\ScholarshipRecipients\ScholarshipRecipientResource;
use App\Models\Scholarship;
use App\Models\Semester;
use App\Services\Finance\ScholarshipApplicationService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Throwable;

class CreateScholarshipRecipient extends CreateRecord
{
    protected static string $resource = ScholarshipRecipientResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $student = auth()->user()?->student;
            $semester = Semester::where('is_active', true)->first();
            $scholarship = Scholarship::find($data['scholarship_id']);

            if (! $student || ! $semester || ! $scholarship) {
                throw new RuntimeException('Data mahasiswa/semester aktif/beasiswa tidak ditemukan.');
            }

            return app(ScholarshipApplicationService::class)->apply($scholarship, $student, $semester);
        } catch (Throwable $e) {
            Notification::make()
                ->title('Gagal mengajukan beasiswa')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }
    }
}

<?php

namespace App\Filament\Student\Resources\ThesisTopics\Pages;

use App\Enums\Thesis\ThesisTopicStatus;
use App\Filament\Student\Resources\ThesisTopics\ThesisTopicResource;
use App\Models\ThesisTopic;
use App\Services\Thesis\ThesisEligibilityService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Throwable;

class CreateThesisTopic extends CreateRecord
{
    protected static string $resource = ThesisTopicResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $student = auth()->user()?->student;

            if (! $student) {
                throw new RuntimeException('Data mahasiswa tidak ditemukan.');
            }

            if (! app(ThesisEligibilityService::class)->isEligible($student)) {
                throw new RuntimeException('Kamu belum bisa mengajukan topik skripsi. Pastikan kamu sudah KRS-kan mata kuliah Skripsi di semester ini.');
            }

            $data['student_id'] = $student->id;
            $data['status'] = ThesisTopicStatus::Draft;

            return ThesisTopic::create($data);
        } catch (Throwable $e) {
            Notification::make()
                ->title('Gagal mengajukan topik')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }
    }
}

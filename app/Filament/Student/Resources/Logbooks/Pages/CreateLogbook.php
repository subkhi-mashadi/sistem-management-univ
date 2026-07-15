<?php

namespace App\Filament\Student\Resources\Logbooks\Pages;

use App\Filament\Student\Resources\Logbooks\LogbookResource;
use App\Models\ThesisTopic;
use App\Services\Thesis\LogbookService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Throwable;

class CreateLogbook extends CreateRecord
{
    protected static string $resource = LogbookResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $student = auth()->user()?->student;
            $topic = ThesisTopic::find($data['thesis_topic_id'] ?? null);

            if (! $student || ! $topic) {
                throw new RuntimeException('Mahasiswa/topik tidak ditemukan.');
            }

            return app(LogbookService::class)->create($student, $topic, $data);
        } catch (Throwable $e) {
            Notification::make()
                ->title('Gagal mencatat bimbingan')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }
    }
}

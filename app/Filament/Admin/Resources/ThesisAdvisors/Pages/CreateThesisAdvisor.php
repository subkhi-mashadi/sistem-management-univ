<?php

namespace App\Filament\Admin\Resources\ThesisAdvisors\Pages;

use App\Filament\Admin\Resources\ThesisAdvisors\Schemas\ThesisAdvisorForm;
use App\Filament\Admin\Resources\ThesisAdvisors\ThesisAdvisorResource;
use App\Models\Lecturer;
use App\Services\Thesis\ThesisAdvisorAssignmentService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Filament\Schemas\Schema;
use Illuminate\Database\Eloquent\Model;
use RuntimeException;
use Throwable;

class CreateThesisAdvisor extends CreateRecord
{
    protected static string $resource = ThesisAdvisorResource::class;

    public function form(Schema $schema): Schema
    {
        return ThesisAdvisorForm::configureCreate($schema);
    }

    protected function handleRecordCreation(array $data): Model
    {
        try {
            $lecturer = Lecturer::find($data['lecturer_id'] ?? null);

            if (! $lecturer || empty($data['student_ids'])) {
                throw new RuntimeException('Dosen/mahasiswa belum dipilih.');
            }

            return app(ThesisAdvisorAssignmentService::class)->assign($lecturer, $data['student_ids']);
        } catch (Throwable $e) {
            Notification::make()
                ->title('Gagal menugaskan pembimbing')
                ->body($e->getMessage())
                ->danger()
                ->send();

            $this->halt();
        }
    }
}

<?php

namespace App\Filament\Lecturer\Resources\Enrollments\Pages;

use App\Filament\Lecturer\Resources\Enrollments\EnrollmentResource;
use App\Enums\Krs\EnrollmentStatus;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewEnrollment extends ViewRecord
{
    protected static string $resource = EnrollmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('approve')
                ->label('Setujui KRS')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->requiresConfirmation()
                ->modalDescription('KRS mahasiswa ini akan disetujui dan dikunci.')
                ->visible(fn () => $this->record->status === EnrollmentStatus::Submitted)
                ->action(function () {
                    $this->record->update(['status' => EnrollmentStatus::Approved->value]);
                    Notification::make()->success()->title('KRS disetujui')->send();
                    $this->refreshFormData(['status', 'approved_at', 'approved_by']);
                }),

            Action::make('reject')
                ->label('Tolak KRS')
                ->icon('heroicon-o-x-circle')
                ->color('danger')
                ->requiresConfirmation()
                ->form([
                    \Filament\Forms\Components\Textarea::make('rejection_notes')
                        ->label('Alasan penolakan')
                        ->required()
                        ->rows(3),
                ])
                ->visible(fn () => $this->record->status === EnrollmentStatus::Submitted)
                ->action(function (array $data) {
                    $this->record->update([
                        'status' => EnrollmentStatus::Rejected->value,
                        'notes' => $data['rejection_notes'],
                    ]);
                    Notification::make()->warning()->title('KRS ditolak')->send();
                    $this->refreshFormData(['status', 'notes']);
                }),
        ];
    }
}

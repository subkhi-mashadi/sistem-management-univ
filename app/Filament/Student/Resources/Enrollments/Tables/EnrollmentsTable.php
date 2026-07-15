<?php

namespace App\Filament\Student\Resources\Enrollments\Tables;

use App\Enums\Krs\EnrollmentStatus;
use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('semester.name')
                    ->label('Semester')
                    ->badge(),
                TextColumn::make('max_sks')
                    ->label('Maks. SKS')
                    ->alignCenter(),
                TextColumn::make('total_sks_taken')
                    ->label('SKS Diambil')
                    ->alignCenter(),
                TextColumn::make('items_count')
                    ->label('Jumlah MK')
                    ->counts('items')
                    ->alignCenter(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof EnrollmentStatus ? $state->value : $state) {
                        'Approved' => 'success',
                        'Submitted' => 'warning',
                        'Rejected' => 'danger',
                        'Locked' => 'gray',
                        default => 'info',
                    }),
                TextColumn::make('approved_at')
                    ->label('Disetujui')
                    ->dateTime()
                    ->placeholder('—'),
            ])
            ->recordActions([
                EditAction::make()->label('Buka'),

                Action::make('cetak_krs')
                    ->label('Cetak KRS')
                    ->icon('heroicon-o-document-arrow-down')
                    ->color('gray')
                    ->visible(fn ($record) => in_array(
                        $record->status instanceof EnrollmentStatus ? $record->status->value : $record->status,
                        [EnrollmentStatus::Approved->value, EnrollmentStatus::Locked->value],
                        true,
                    ))
                    ->action(function ($record) {
                        $enrollment = Enrollment::with([
                            'semester',
                            'student.user',
                            'student.studyProgram.faculty',
                            'student.advisor.user',
                            'items.courseOffering.course',
                            'items.courseOffering.schedules.classroom',
                            'approver',
                        ])->find($record->id);

                        $pdf = Pdf::loadView('pdf.krs', compact('enrollment'))
                            ->setPaper('a4', 'portrait');

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            'KRS-'.$enrollment->student->nim.'-'.str_replace(['/', '\\', ' '], ['-', '-', '_'], $enrollment->semester->name).'.pdf',
                        );
                    }),

                Action::make('submit')
                    ->label('Submit ke Dosen Wali')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->modalDescription('KRS akan dikunci sementara dan menunggu approval Dosen Wali.')
                    ->visible(fn ($record) => in_array(
                        $record->status instanceof EnrollmentStatus ? $record->status->value : $record->status,
                        ['Draft', 'Rejected'],
                        true,
                    ))
                    ->action(function ($record) {
                        if ($record->items()->count() === 0) {
                            Notification::make()
                                ->danger()
                                ->title('Tidak bisa submit')
                                ->body('Anda belum memilih mata kuliah.')
                                ->send();

                            return;
                        }
                        $record->update(['status' => EnrollmentStatus::Submitted->value]);
                        Notification::make()
                            ->success()
                            ->title('KRS disubmit')
                            ->body('Menunggu approval Dosen Wali.')
                            ->send();
                    }),
            ]);
    }
}

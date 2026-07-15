<?php

namespace App\Filament\Admin\Resources\Enrollments\Tables;

use App\Enums\Krs\EnrollmentStatus;
use App\Models\Enrollment;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('semester.name')
                    ->label('Semester')
                    ->searchable(),
                TextColumn::make('max_sks')
                    ->label('SKS Maksimum')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('total_sks_taken')
                    ->label('Total SKS Diambil')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('approved_by')
                    ->label('Disetujui Oleh')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->label('Tanggal Disetujui')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')->options(EnrollmentStatus::class),
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
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
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

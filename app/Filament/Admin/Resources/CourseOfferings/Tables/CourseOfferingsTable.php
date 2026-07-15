<?php

namespace App\Filament\Admin\Resources\CourseOfferings\Tables;

use App\Enums\Scheduling\ClassSessionStatus;
use App\Models\ClassSession;
use App\Models\Lecturer;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class CourseOfferingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('course.name')
                    ->label('Mata Kuliah')
                    ->searchable(),
                TextColumn::make('semester.name')
                    ->label('Semester')
                    ->searchable(),
                TextColumn::make('class_code')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('lecturer_ids')
                    ->label('Dosen Pengampu')
                    ->getStateUsing(function ($record): string {
                        $ids = $record->lecturer_ids ?? [];
                        if (empty($ids)) {
                            return '—';
                        }

                        return Lecturer::whereIn('id', $ids)
                            ->with('user:id,full_name,name')
                            ->get()
                            ->map(fn (Lecturer $l) => $l->user?->full_name ?? $l->user?->name ?? "#{$l->id}")
                            ->join(', ');
                    })
                    ->wrap(),
                TextColumn::make('quota')
                    ->label('Kuota')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('enrolled_count')
                    ->label('Terdaftar')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('sessions_count')
                    ->label('Sesi')
                    ->counts('schedules')
                    ->getStateUsing(fn ($record) => ClassSession::whereHas(
                        'schedule', fn ($q) => $q->where('course_offering_id', $record->id)
                    )->count())
                    ->alignCenter(),
                IconColumn::make('is_open')
                    ->label('Dibuka')
                    ->boolean(),
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
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('generate_sessions')
                    ->label('Generate Sesi')
                    ->icon('heroicon-o-calendar-days')
                    ->color('info')
                    ->requiresConfirmation()
                    ->modalHeading('Generate 14 Sesi Kelas')
                    ->modalDescription('Akan dibuat 14 ClassSession untuk tiap jadwal kelas ini, dihitung dari tanggal mulai semester. Sesi yang sudah ada tidak akan digandakan.')
                    ->action(function ($record) {
                        $record->load(['schedules', 'semester']);
                        $semester = $record->semester;

                        if (! $semester?->start_date) {
                            Notification::make()->danger()
                                ->title('Semester belum punya tanggal mulai.')
                                ->send();

                            return;
                        }

                        $created = 0;

                        foreach ($record->schedules as $schedule) {
                            // Hari-1 semester yang cocok dengan day_of_week jadwal
                            $dayMap = [
                                'Senin' => 1, 'Selasa' => 2, 'Rabu' => 3,
                                'Kamis' => 4, 'Jumat' => 5, 'Sabtu' => 6, 'Minggu' => 0,
                            ];
                            $targetDow = $dayMap[$schedule->day_of_week->value] ?? 1;
                            $semStart  = $semester->start_date->copy();

                            // Cari hari pertama yang sesuai mulai dari tanggal awal semester
                            $firstDay = $semStart->copy();
                            while ($firstDay->dayOfWeek !== $targetDow) {
                                $firstDay->addDay();
                            }

                            $existing = ClassSession::where('schedule_id', $schedule->id)
                                ->pluck('meeting_number')->all();

                            for ($i = 1; $i <= 14; $i++) {
                                if (in_array($i, $existing, true)) {
                                    continue;
                                }
                                $sessionDate = $firstDay->copy()->addWeeks($i - 1);
                                ClassSession::create([
                                    'schedule_id'    => $schedule->id,
                                    'meeting_number' => $i,
                                    'session_date'   => $sessionDate,
                                    'start_time'     => $schedule->start_time,
                                    'end_time'       => $schedule->end_time,
                                    'status'         => ClassSessionStatus::Scheduled->value,
                                ]);
                                $created++;
                            }
                        }

                        Notification::make()->success()
                            ->title("{$created} sesi berhasil dibuat.")
                            ->send();
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

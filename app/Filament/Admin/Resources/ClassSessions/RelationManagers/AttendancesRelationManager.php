<?php

namespace App\Filament\Admin\Resources\ClassSessions\RelationManagers;

use App\Enums\Scheduling\AttendanceStatus;
use App\Enums\Scheduling\CheckMethod;
use App\Models\Attendance;
use App\Models\KrsItem;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Schema;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AttendancesRelationManager extends RelationManager
{
    protected static string $relationship = 'attendances';

    protected static ?string $title = 'Presensi Mahasiswa';

    public function form(Schema $schema): Schema
    {
        return $schema->columns(2)->components([
            Select::make('status')
                ->label('Status')
                ->options(AttendanceStatus::class)
                ->default(AttendanceStatus::Hadir->value)
                ->required()
                ->columnSpan(1),

            Select::make('check_method')
                ->label('Metode')
                ->options(CheckMethod::class)
                ->default(CheckMethod::cases()[0]->value ?? 'Manual')
                ->columnSpan(1),

            Textarea::make('notes')
                ->label('Catatan')
                ->rows(2)
                ->columnSpanFull(),
        ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('id')
            ->defaultSort('id')
            ->columns([
                TextColumn::make('student.nim')
                    ->label('NIM')
                    ->searchable(),
                TextColumn::make('student.user.full_name')
                    ->label('Nama Mahasiswa')
                    ->searchable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options(AttendanceStatus::class),
                TextColumn::make('check_method')
                    ->label('Metode')
                    ->badge()
                    ->placeholder('—'),
                TextColumn::make('notes')
                    ->label('Catatan')
                    ->placeholder('—')
                    ->limit(30),
            ])
            ->headerActions([
                Action::make('bulk_fill')
                    ->label('Isi Presensi Semua')
                    ->icon('heroicon-o-user-group')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Isi Presensi Semua Mahasiswa')
                    ->modalDescription('Mahasiswa yang belum ada presensinya akan ditambahkan. Yang sudah ada tidak berubah.')
                    ->form([
                        Select::make('default_status')
                            ->label('Status Default')
                            ->options(AttendanceStatus::class)
                            ->default(AttendanceStatus::Hadir->value)
                            ->required(),
                        Select::make('check_method')
                            ->label('Metode Absen')
                            ->options(CheckMethod::class)
                            ->default(CheckMethod::cases()[0]->value ?? 'Manual'),
                    ])
                    ->action(function (array $data) {
                        $session    = $this->getOwnerRecord();
                        $schedule   = $session->schedule;
                        $offeringId = $schedule?->course_offering_id;

                        if (! $offeringId) {
                            Notification::make()->danger()->title('Schedule tidak terhubung ke CourseOffering.')->send();

                            return;
                        }

                        // Semua mahasiswa yang punya KrsItem aktif di kelas ini
                        $studentIds = KrsItem::whereHas(
                            'enrollment',
                            fn ($q) => $q->where('semester_id', $schedule->courseOffering?->semester_id)
                        )
                            ->where('course_offering_id', $offeringId)
                            ->whereIn('status', ['Active'])
                            ->pluck('enrollment_id')
                            ->pipe(fn ($ids) => KrsItem::whereIn('enrollment_id', $ids)
                                ->where('course_offering_id', $offeringId)
                                ->join('enrollments', 'enrollments.id', '=', 'krs_items.enrollment_id')
                                ->pluck('enrollments.student_id'))
                            ->unique()->values();

                        $existing = Attendance::where('class_session_id', $session->id)
                            ->pluck('student_id')->all();

                        $created = 0;
                        foreach ($studentIds as $sid) {
                            if (in_array($sid, $existing, true)) {
                                continue;
                            }
                            Attendance::create([
                                'class_session_id' => $session->id,
                                'student_id'       => $sid,
                                'status'           => $data['default_status'],
                                'check_method'     => $data['check_method'] ?? null,
                            ]);
                            $created++;
                        }

                        Notification::make()->success()
                            ->title("{$created} presensi berhasil dibuat.")
                            ->send();
                    }),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}

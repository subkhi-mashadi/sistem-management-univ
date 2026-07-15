<?php

namespace App\Filament\Student\Resources\Schedules;

use App\Enums\Scheduling\AttendanceStatus;
use App\Enums\Scheduling\CheckMethod;
use App\Enums\Scheduling\DayOfWeek;
use App\Filament\Student\Resources\Schedules\Pages\ListSchedules;
use App\Models\Attendance;
use App\Models\ClassSession;
use App\Models\Enrollment;
use App\Models\KrsItem;
use App\Models\Schedule;
use App\Models\Semester;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\TextInput;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    protected static ?string $navigationLabel = 'Jadwal Kuliah';

    protected static ?string $modelLabel = 'Jadwal';

    protected static ?string $pluralModelLabel = 'Jadwal Kuliah';

    protected static ?int $navigationSort = 4;

    public static function getEloquentQuery(): Builder
    {
        $studentId      = auth()->user()?->student?->id;
        $activeSemesterId = Semester::where('is_active', true)->value('id');

        $offeringIds = Enrollment::where('student_id', $studentId)
            ->where('semester_id', $activeSemesterId)
            ->with('items')
            ->get()
            ->flatMap(fn ($e) => $e->items->pluck('course_offering_id'))
            ->unique()
            ->all();

        return parent::getEloquentQuery()
            ->whereIn('course_offering_id', $offeringIds)
            ->with(['courseOffering.course', 'classroom']);
    }

    public static function table(Table $table): Table
    {
        $studentId = auth()->user()?->student?->id;
        $todayDay  = now()->locale('id')->isoFormat('dddd'); // Senin, Selasa, …

        // Map Carbon isoFormat to enum value
        $map = [1 => 'Senin', 2 => 'Selasa', 3 => 'Rabu', 4 => 'Kamis', 5 => 'Jumat', 6 => 'Sabtu', 7 => 'Minggu'];
        $defaultDay = $map[now()->dayOfWeekIso] ?? 'Senin';

        return $table
            ->defaultSort('start_time')
            ->contentGrid(['md' => 2, 'xl' => 3])
            ->columns([
                TextColumn::make('day_of_week')
                    ->label('Hari')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('start_time')
                    ->label('Jam')
                    ->formatStateUsing(fn ($record) =>
                        \Carbon\Carbon::parse($record->start_time)->format('H:i')
                        .' – '
                        .\Carbon\Carbon::parse($record->end_time)->format('H:i')
                    ),

                TextColumn::make('courseOffering.course.code')
                    ->label('Kode MK')
                    ->badge()
                    ->color('gray'),

                TextColumn::make('courseOffering.course.name')
                    ->label('Mata Kuliah')
                    ->weight('bold')
                    ->wrap(),

                TextColumn::make('courseOffering.class_code')
                    ->label('Kelas')
                    ->badge()
                    ->color('info'),

                TextColumn::make('courseOffering.course.total_sks')
                    ->label('SKS')
                    ->badge()
                    ->color('warning'),

                TextColumn::make('classroom.name')
                    ->label('Ruangan')
                    ->placeholder('—')
                    ->getStateUsing(function (Schedule $record) {
                        $session = $record->sessions()
                            ->whereNotNull('classroom_id')
                            ->latest('session_date')
                            ->first();

                        return $session?->classroom?->name ?? $record->classroom?->name ?? '—';
                    }),

                TextColumn::make('attendance_status')
                    ->label('Presensi Hari Ini')
                    ->badge()
                    ->getStateUsing(function (Schedule $record) {
                        $sid = auth()->user()?->student?->id;

                        // Cari session aktif (ada token) atau session paling terakhir
                        $session = $record->sessions()
                            ->whereNotNull('attendance_token')
                            ->where('token_expires_at', '>', now())
                            ->latest('session_date')
                            ->first()
                            ?? $record->sessions()
                                ->latest('session_date')
                                ->first();

                        if (! $session) return '—';

                        $attendance = Attendance::where('class_session_id', $session->id)
                            ->where('student_id', $sid)
                            ->first();

                        if ($attendance) return $attendance->status?->value ?? '—';

                        return $session->isTokenActive() ? 'Token Aktif' : 'Belum Absen';
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'Hadir'        => 'success',
                        'Terlambat'    => 'warning',
                        'Sakit', 'Izin' => 'info',
                        'Alpa'         => 'danger',
                        'Token Aktif'  => 'primary',
                        default        => 'gray',
                    }),
            ])
            ->filters([
                SelectFilter::make('day_of_week')
                    ->label('Hari')
                    ->options(DayOfWeek::class)
                    ->default($defaultDay),
            ])
            ->headerActions([
                Action::make('input_token')
                    ->label('Input Token Presensi')
                    ->icon(Heroicon::OutlinedKey)
                    ->color('success')
                    ->schema([
                        TextInput::make('token')
                            ->label('Kode Token')
                            ->required()
                            ->maxLength(8)
                            ->placeholder('Contoh: AB3K9Z')
                            ->extraInputAttributes([
                                'style' => 'text-transform:uppercase;letter-spacing:0.3em;font-size:1.5rem;text-align:center;font-weight:bold',
                            ])
                            ->dehydrateStateUsing(fn ($state) => strtoupper(trim($state ?? ''))),
                    ])
                    ->modalHeading('Input Token Presensi')
                    ->modalDescription('Masukkan kode token yang diberikan dosen untuk mencatat kehadiran.')
                    ->modalSubmitActionLabel('Absen Sekarang')
                    ->action(function (array $data) use ($studentId) {
                        $token = strtoupper(trim($data['token']));

                        $session = ClassSession::where('attendance_token', $token)
                            ->with('schedule.courseOffering')
                            ->first();

                        if (! $session) {
                            Notification::make()->title('Token tidak valid')->danger()->send();
                            return;
                        }

                        if (! $session->isTokenActive()) {
                            Notification::make()->title('Token sudah kedaluwarsa')->warning()->send();
                            return;
                        }

                        $offeringId = $session->schedule?->courseOffering?->id;
                        $enrolled = KrsItem::whereHas('enrollment', fn ($q) => $q->where('student_id', $studentId))
                            ->where('course_offering_id', $offeringId)
                            ->exists();

                        if (! $enrolled) {
                            Notification::make()->title('Anda tidak terdaftar di kelas ini')->danger()->send();
                            return;
                        }

                        if (Attendance::where('class_session_id', $session->id)->where('student_id', $studentId)->exists()) {
                            Notification::make()->title('Presensi sudah tercatat')->info()->send();
                            return;
                        }

                        Attendance::create([
                            'class_session_id' => $session->id,
                            'student_id'       => $studentId,
                            'status'           => AttendanceStatus::Hadir,
                            'check_method'     => CheckMethod::Token,
                            'check_in_at'      => now(),
                            'created_by'       => auth()->id(),
                        ]);

                        Notification::make()
                            ->title('Presensi Berhasil!')
                            ->body('Kehadiran Anda telah dicatat untuk kelas '
                                .($session->schedule?->courseOffering?->course?->name ?? ''))
                            ->success()
                            ->send();
                    }),
            ])
            ->recordActions([
                Action::make('detail')
                    ->label('Detail')
                    ->icon(Heroicon::OutlinedCalendar)
                    ->modalHeading(fn (Schedule $record) => $record->courseOffering?->course?->name ?? 'Detail Jadwal')
                    ->schema(fn (Schedule $record) => [
                        Section::make('Informasi Jadwal')
                            ->columns(2)
                            ->schema([
                                TextEntry::make('day_of_week')
                                    ->label('Hari')
                                    ->badge()
                                    ->color('primary')
                                    ->state(fn () => $record->day_of_week instanceof \BackedEnum
                                        ? $record->day_of_week->value
                                        : (string) $record->day_of_week),

                                TextEntry::make('jam')
                                    ->label('Jam')
                                    ->state(fn () =>
                                        \Carbon\Carbon::parse($record->start_time)->format('H:i')
                                        .' – '
                                        .\Carbon\Carbon::parse($record->end_time)->format('H:i')),

                                TextEntry::make('courseOffering.course.code')
                                    ->label('Kode MK')
                                    ->badge()
                                    ->getStateUsing(fn () => $record->courseOffering?->course?->code ?? '—'),

                                TextEntry::make('courseOffering.class_code')
                                    ->label('Kelas')
                                    ->badge()
                                    ->color('info')
                                    ->getStateUsing(fn () => $record->courseOffering?->class_code ?? '—'),

                                TextEntry::make('courseOffering.course.total_sks')
                                    ->label('SKS')
                                    ->badge()
                                    ->color('warning')
                                    ->getStateUsing(fn () => ($record->courseOffering?->course?->total_sks ?? '—').' SKS'),

                                TextEntry::make('classroom.name')
                                    ->label('Ruangan')
                                    ->getStateUsing(fn () => $record->classroom?->name ?? '—'),
                            ]),

                        Section::make('5 Sesi Terakhir')
                            ->schema([
                                RepeatableEntry::make('recent_sessions')
                                    ->label('')
                                    ->getStateUsing(fn () => $record
                                        ->sessions()
                                        ->latest('session_date')
                                        ->limit(5)
                                        ->get()
                                        ->map(function ($s) {
                                            $sid = Auth::user()?->student?->id;
                                            $att = \App\Models\Attendance::where('class_session_id', $s->id)
                                                ->where('student_id', $sid)->first();
                                            return [
                                                'pertemuan'   => 'Ke-'.$s->meeting_number,
                                                'tanggal'     => $s->session_date?->format('d M Y') ?? '—',
                                                'topik'       => $s->topic ?? '—',
                                                'status_sesi' => $s->status instanceof \BackedEnum ? $s->status->value : (string) $s->status,
                                                'presensi'    => $att?->status?->value ?? '—',
                                            ];
                                        })->toArray()
                                    )
                                    ->columns(5)
                                    ->schema([
                                        TextEntry::make('pertemuan')->label('Pertemuan')->badge(),
                                        TextEntry::make('tanggal')->label('Tanggal'),
                                        TextEntry::make('topik')->label('Topik'),
                                        TextEntry::make('status_sesi')->label('Status Sesi')->badge(),
                                        TextEntry::make('presensi')->label('Presensi')->badge()
                                            ->color(fn (string $state) => match ($state) {
                                                'Hadir'     => 'success',
                                                'Terlambat' => 'warning',
                                                'Alpa'      => 'danger',
                                                'Sakit', 'Izin' => 'info',
                                                default     => 'gray',
                                            }),
                                    ]),
                            ]),
                    ])
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup'),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSchedules::route('/'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

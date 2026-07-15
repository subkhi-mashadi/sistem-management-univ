<?php

namespace App\Filament\Lecturer\Resources\ClassSessions;

use App\Enums\Scheduling\ClassSessionStatus;
use App\Filament\Admin\Resources\ClassSessions\RelationManagers\AttendancesRelationManager;
use App\Filament\Lecturer\Resources\ClassSessions\Pages\EditClassSession;
use App\Filament\Lecturer\Resources\ClassSessions\Pages\ListClassSessions;
use App\Models\ClassSession;
use App\Models\Classroom;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class ClassSessionResource extends Resource
{
    protected static ?string $model = ClassSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    protected static ?string $navigationLabel = 'Sesi Kelas';

    protected static ?string $modelLabel = 'Sesi Kelas';

    protected static ?string $pluralModelLabel = 'Sesi Kelas';

    protected static ?int $navigationSort = 2;

    /**
     * Dosen hanya lihat sesi dari jadwal milik kelas yang diajarnya.
     */
    public static function getEloquentQuery(): Builder
    {
        $lecturerId = auth()->user()?->lecturer?->id;

        return parent::getEloquentQuery()
            ->whereHas('schedule.courseOffering', function (Builder $q) use ($lecturerId) {
                $q->whereJsonContains('lecturer_ids', $lecturerId);
            });
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Informasi Sesi')
                ->columnSpanFull()
                ->columns(2)
                ->schema([
                    TextInput::make('meeting_number')->label('Pertemuan Ke')->numeric()->disabled(),
                    DatePicker::make('session_date')->label('Tanggal Sesi')->required(),
                    TimePicker::make('start_time')->label('Jam Mulai')->required(),
                    TimePicker::make('end_time')->label('Jam Selesai')->required(),
                    Select::make('status')
                        ->label('Status')
                        ->options(ClassSessionStatus::class)
                        ->required(),
                    Select::make('classroom_id')
                        ->label('Ruangan (Override)')
                        ->options(Classroom::pluck('name', 'id'))
                        ->searchable()
                        ->placeholder('Gunakan ruangan dari jadwal')
                        ->columnSpanFull(),
                    TextInput::make('topic')->label('Topik Bahasan')->columnSpanFull(),
                    Textarea::make('material_summary')
                        ->label('Ringkasan Materi')
                        ->rows(3)
                        ->columnSpanFull(),
                    Textarea::make('notes')->label('Catatan')->rows(2)->columnSpanFull(),
                    FileUpload::make('material_files')
                        ->label('File Materi')
                        ->multiple()
                        ->directory('materials')
                        ->acceptedFileTypes(['application/pdf', 'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'application/vnd.ms-powerpoint',
                            'application/vnd.openxmlformats-officedocument.presentationml.presentation',
                            'image/*'])
                        ->maxSize(10240)
                        ->downloadable()
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('meeting_number', 'asc')
            ->columns([
                TextColumn::make('schedule.courseOffering.course.name')
                    ->label('Mata Kuliah')
                    ->searchable()
                    ->wrap(),
                TextColumn::make('schedule.courseOffering.class_code')
                    ->label('Kelas')
                    ->badge(),
                TextColumn::make('meeting_number')
                    ->label('Pertemuan')
                    ->alignCenter(),
                TextColumn::make('session_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),
                TextColumn::make('start_time')->label('Mulai')->alignCenter(),
                TextColumn::make('end_time')->label('Selesai')->alignCenter(),
                TextColumn::make('topic')->label('Topik')->limit(30)->placeholder('—'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof \BackedEnum ? $state->value : (string) $state) {
                        'Conducted'   => 'success',
                        'Cancelled'   => 'danger',
                        'Substituted' => 'warning',
                        default       => 'info',
                    }),
                TextColumn::make('attendances_count')
                    ->label('Presensi')
                    ->counts('attendances')
                    ->alignCenter(),
                IconColumn::make('attendance_token')
                    ->label('Token')
                    ->boolean()
                    ->trueIcon(Heroicon::OutlinedKey)
                    ->falseIcon(Heroicon::OutlinedLockClosed)
                    ->trueColor('success')
                    ->falseColor('gray')
                    ->state(fn (ClassSession $record) => $record->isTokenActive()),
            ])
            ->filters([
                SelectFilter::make('status')->options(ClassSessionStatus::class),
                SelectFilter::make('meeting_number')
                    ->label('Pertemuan')
                    ->options(fn () => collect(range(1, 14))->mapWithKeys(fn ($n) => [$n => 'Pertemuan '.$n])),
                SelectFilter::make('course')
                    ->label('Mata Kuliah')
                    ->query(fn ($query, array $data) =>
                        $data['value'] ? $query->whereHas('schedule.courseOffering', fn ($q) => $q->where('id', $data['value'])) : $query
                    )
                    ->options(function () {
                        $lecturerId = auth()->user()?->lecturer?->id;
                        return \App\Models\CourseOffering::whereJsonContains('lecturer_ids', $lecturerId)
                            ->with('course')
                            ->get()
                            ->mapWithKeys(fn ($o) => [$o->id => ($o->course?->code.' - '.$o->course?->name.' ('.$o->class_code.')')]);
                    }),
            ])
            ->recordActions([
                Action::make('open_token')
                    ->label('Buka Presensi')
                    ->icon(Heroicon::OutlinedKey)
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Generate Token Presensi')
                    ->modalDescription('Token berlaku 30 menit. Tampilkan ke mahasiswa untuk absen.')
                    ->modalSubmitActionLabel('Generate Token')
                    ->action(function (ClassSession $record) {
                        $record->generateAttendanceToken(30);
                        Notification::make()
                            ->title('Token Presensi Dibuat')
                            ->body('Token: **'.$record->attendance_token.'** (berlaku 30 menit)')
                            ->success()
                            ->persistent()
                            ->send();
                    })
                    ->visible(fn (ClassSession $record) => ! $record->isTokenActive()),

                Action::make('show_token')
                    ->label(fn (ClassSession $record) => 'Token: '.$record->attendance_token)
                    ->icon(Heroicon::OutlinedEye)
                    ->color('warning')
                    ->modalHeading('Token Presensi Aktif')
                    ->modalContent(fn (ClassSession $record) => view('filament.lecturer.token-modal', ['session' => $record]))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('Tutup')
                    ->extraModalFooterActions([
                        Action::make('close_token')
                            ->label('Tutup Presensi')
                            ->color('danger')
                            ->action(function (ClassSession $record) {
                                $record->update(['attendance_token' => null, 'token_expires_at' => null]);
                                Notification::make()->title('Presensi Ditutup')->warning()->send();
                            })
                            ->requiresConfirmation(),
                    ])
                    ->visible(fn (ClassSession $record) => $record->isTokenActive()),

                EditAction::make()->label('Edit')->icon(Heroicon::OutlinedPencil),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            AttendancesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClassSessions::route('/'),
            'edit'  => EditClassSession::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

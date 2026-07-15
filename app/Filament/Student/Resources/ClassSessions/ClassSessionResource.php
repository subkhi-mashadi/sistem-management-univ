<?php

namespace App\Filament\Student\Resources\ClassSessions;

use App\Enums\Scheduling\ClassSessionStatus;
use App\Filament\Student\Resources\ClassSessions\Pages\ListClassSessions;
use App\Models\ClassSession;
use App\Models\Enrollment;
use App\Models\KrsItem;
use App\Models\Semester;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Storage;

class ClassSessionResource extends Resource
{
    protected static ?string $model = ClassSession::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPresentationChartBar;

    protected static ?string $navigationLabel = 'Materi & Sesi';

    protected static ?string $modelLabel = 'Sesi Kuliah';

    protected static ?string $pluralModelLabel = 'Materi & Sesi';

    protected static ?int $navigationSort = 5;

    public static function getEloquentQuery(): Builder
    {
        $studentId        = auth()->user()?->student?->id;
        $activeSemesterId = Semester::where('is_active', true)->value('id');

        $offeringIds = Enrollment::where('student_id', $studentId)
            ->where('semester_id', $activeSemesterId)
            ->with('items')
            ->get()
            ->flatMap(fn ($e) => $e->items->pluck('course_offering_id'))
            ->unique()
            ->all();

        return parent::getEloquentQuery()
            ->whereHas('schedule', fn ($q) => $q->whereIn('course_offering_id', $offeringIds))
            ->with(['schedule.courseOffering.course', 'schedule.classroom', 'classroom']);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('meeting_number', 'asc')
            ->columns([
                TextColumn::make('session_date')
                    ->label('Tanggal')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('schedule.courseOffering.course.code')
                    ->label('MK')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('schedule.courseOffering.course.name')
                    ->label('Mata Kuliah')
                    ->wrap()
                    ->searchable(),

                TextColumn::make('meeting_number')
                    ->label('Pertemuan')
                    ->alignCenter()
                    ->badge(),

                TextColumn::make('topic')
                    ->label('Topik')
                    ->limit(40)
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof \BackedEnum ? $state->value : (string) $state) {
                        'Conducted'   => 'success',
                        'Cancelled'   => 'danger',
                        'Substituted' => 'warning',
                        default       => 'info',
                    }),

                TextColumn::make('material_files')
                    ->label('File')
                    ->getStateUsing(fn (ClassSession $record) => count($record->material_files ?? []))
                    ->suffix(' file')
                    ->badge()
                    ->color(fn (string $state) => (int) $state > 0 ? 'success' : 'gray'),
            ])
            ->filters([
                SelectFilter::make('status')->options(ClassSessionStatus::class),
                SelectFilter::make('meeting_number')
                    ->label('Pertemuan')
                    ->options(fn () => collect(range(1, 14))->mapWithKeys(fn ($n) => [$n => 'Pertemuan '.$n])),
            ])
            ->recordActions([
                ViewAction::make()
                    ->label('Lihat Detail')
                    ->modalHeading(fn (ClassSession $record) => 'Pertemuan '.$record->meeting_number.' — '.$record->topic)
                    ->modalContent(fn (ClassSession $record) => view('filament.student.class-session-detail', ['session' => $record])),
            ]);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListClassSessions::route('/'),
        ];
    }

    public static function canCreate(): bool { return false; }
    public static function canEdit($record): bool { return false; }
    public static function canDelete($record): bool { return false; }
}

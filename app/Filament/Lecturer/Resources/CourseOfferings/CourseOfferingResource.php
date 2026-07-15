<?php

namespace App\Filament\Lecturer\Resources\CourseOfferings;

use App\Filament\Lecturer\Resources\CourseOfferings\Pages\EditCourseOffering;
use App\Filament\Lecturer\Resources\CourseOfferings\Pages\ListCourseOfferings;
use App\Filament\Lecturer\Resources\CourseOfferings\RelationManagers\GradesRelationManager;
use App\Models\CourseOffering;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Actions\EditAction;
use Illuminate\Database\Eloquent\Builder;

class CourseOfferingResource extends Resource
{
    protected static ?string $model = CourseOffering::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    protected static ?string $navigationLabel = 'Kelas Saya';

    protected static ?string $modelLabel = 'Kelas';

    protected static ?string $pluralModelLabel = 'Kelas Saya';

    protected static ?int $navigationSort = 1;

    public static function getEloquentQuery(): Builder
    {
        $lecturerId = auth()->user()?->lecturer?->id;

        return parent::getEloquentQuery()
            ->whereJsonContains('lecturer_ids', $lecturerId);
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('semester_id', 'desc')
            ->columns([
                TextColumn::make('course.code')->label('Kode MK')->badge(),
                TextColumn::make('course.name')->label('Mata Kuliah')->wrap()->searchable(),
                TextColumn::make('semester.name')->label('Semester')->badge(),
                TextColumn::make('class_code')->label('Kelas')->badge(),
                TextColumn::make('course.total_sks')->label('SKS')->alignCenter(),
                TextColumn::make('enrolled_count')->label('Mhs Terdaftar')->alignCenter(),
                IconColumn::make('is_open')->label('Buka')->boolean(),
            ])
            ->recordActions([
                EditAction::make()->label('Input Nilai'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            GradesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourseOfferings::route('/'),
            'edit'  => EditCourseOffering::route('/{record}/edit'),
        ];
    }

    public static function canCreate(): bool
    {
        return false;
    }
}

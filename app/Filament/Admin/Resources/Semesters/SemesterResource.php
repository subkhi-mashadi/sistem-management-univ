<?php

namespace App\Filament\Admin\Resources\Semesters;

use App\Filament\Admin\Resources\Semesters\Pages\CreateSemester;
use App\Filament\Admin\Resources\Semesters\Pages\EditSemester;
use App\Filament\Admin\Resources\Semesters\Pages\ListSemesters;
use App\Filament\Admin\Resources\Semesters\Schemas\SemesterForm;
use App\Filament\Admin\Resources\Semesters\Tables\SemestersTable;
use App\Models\Semester;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SemesterResource extends Resource
{
    protected static ?string $model = Semester::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Semester';

    protected static ?string $modelLabel = 'Semester';

    protected static ?string $pluralModelLabel = 'Semester';

    protected static ?int $navigationSort = 19;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendarDays;

    public static function form(Schema $schema): Schema
    {
        return SemesterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SemestersTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSemesters::route('/'),
            'create' => CreateSemester::route('/create'),
            'edit' => EditSemester::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources\Faculties;

use App\Filament\Admin\Resources\Faculties\Pages\CreateFaculty;
use App\Filament\Admin\Resources\Faculties\Pages\EditFaculty;
use App\Filament\Admin\Resources\Faculties\Pages\ListFaculties;
use App\Filament\Admin\Resources\Faculties\Schemas\FacultyForm;
use App\Filament\Admin\Resources\Faculties\Tables\FacultiesTable;
use App\Models\Faculty;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FacultyResource extends Resource
{
    protected static ?string $model = Faculty::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Fakultas';

    protected static ?string $modelLabel = 'Fakultas';

    protected static ?string $pluralModelLabel = 'Fakultas';

    protected static ?int $navigationSort = 10;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingLibrary;

    public static function form(Schema $schema): Schema
    {
        return FacultyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FacultiesTable::configure($table);
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
            'index' => ListFaculties::route('/'),
            'create' => CreateFaculty::route('/create'),
            'edit' => EditFaculty::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

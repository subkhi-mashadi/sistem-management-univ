<?php

namespace App\Filament\Admin\Resources\Lecturers;

use App\Filament\Admin\Resources\Lecturers\Pages\CreateLecturer;
use App\Filament\Admin\Resources\Lecturers\Pages\EditLecturer;
use App\Filament\Admin\Resources\Lecturers\Pages\ListLecturers;
use App\Filament\Admin\Resources\Lecturers\Schemas\LecturerForm;
use App\Filament\Admin\Resources\Lecturers\Tables\LecturersTable;
use App\Models\Lecturer;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LecturerResource extends Resource
{
    protected static ?string $model = Lecturer::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Dosen';

    protected static ?string $modelLabel = 'Dosen';

    protected static ?string $pluralModelLabel = 'Dosen';

    protected static ?int $navigationSort = 15;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedIdentification;

    public static function form(Schema $schema): Schema
    {
        return LecturerForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LecturersTable::configure($table);
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
            'index' => ListLecturers::route('/'),
            'create' => CreateLecturer::route('/create'),
            'edit' => EditLecturer::route('/{record}/edit'),
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

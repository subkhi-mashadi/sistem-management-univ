<?php

namespace App\Filament\Admin\Resources\Curricula;

use App\Filament\Admin\Resources\Curricula\Pages\CreateCurriculum;
use App\Filament\Admin\Resources\Curricula\Pages\EditCurriculum;
use App\Filament\Admin\Resources\Curricula\Pages\ListCurricula;
use App\Filament\Admin\Resources\Curricula\Schemas\CurriculumForm;
use App\Filament\Admin\Resources\Curricula\Tables\CurriculaTable;
use App\Models\Curriculum;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CurriculumResource extends Resource
{
    protected static ?string $model = Curriculum::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Kurikulum';

    protected static ?string $modelLabel = 'Kurikulum';

    protected static ?string $pluralModelLabel = 'Kurikulum';

    protected static ?int $navigationSort = 12;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function form(Schema $schema): Schema
    {
        return CurriculumForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CurriculaTable::configure($table);
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
            'index' => ListCurricula::route('/'),
            'create' => CreateCurriculum::route('/create'),
            'edit' => EditCurriculum::route('/{record}/edit'),
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

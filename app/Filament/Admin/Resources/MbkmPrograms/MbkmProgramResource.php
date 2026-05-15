<?php

namespace App\Filament\Admin\Resources\MbkmPrograms;

use App\Filament\Admin\Resources\MbkmPrograms\Pages\CreateMbkmProgram;
use App\Filament\Admin\Resources\MbkmPrograms\Pages\EditMbkmProgram;
use App\Filament\Admin\Resources\MbkmPrograms\Pages\ListMbkmPrograms;
use App\Filament\Admin\Resources\MbkmPrograms\Schemas\MbkmProgramForm;
use App\Filament\Admin\Resources\MbkmPrograms\Tables\MbkmProgramsTable;
use App\Models\MbkmProgram;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class MbkmProgramResource extends Resource
{
    protected static ?string $model = MbkmProgram::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Tugas Akhir & MBKM';

    protected static ?string $navigationLabel = 'Program MBKM';

    protected static ?string $modelLabel = 'Program MBKM';

    protected static ?string $pluralModelLabel = 'Program MBKM';

    protected static ?int $navigationSort = 74;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedGlobeAlt;

    public static function form(Schema $schema): Schema
    {
        return MbkmProgramForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MbkmProgramsTable::configure($table);
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
            'index' => ListMbkmPrograms::route('/'),
            'create' => CreateMbkmProgram::route('/create'),
            'edit' => EditMbkmProgram::route('/{record}/edit'),
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

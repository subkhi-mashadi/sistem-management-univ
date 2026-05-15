<?php

namespace App\Filament\Admin\Resources\GradeSchemas;

use App\Filament\Admin\Resources\GradeSchemas\Pages\CreateGradeSchema;
use App\Filament\Admin\Resources\GradeSchemas\Pages\EditGradeSchema;
use App\Filament\Admin\Resources\GradeSchemas\Pages\ListGradeSchemas;
use App\Filament\Admin\Resources\GradeSchemas\Schemas\GradeSchemaForm;
use App\Filament\Admin\Resources\GradeSchemas\Tables\GradeSchemasTable;
use App\Models\GradeSchema;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GradeSchemaResource extends Resource
{
    protected static ?string $model = GradeSchema::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Skema Nilai';

    protected static ?string $modelLabel = 'Skema Nilai';

    protected static ?string $pluralModelLabel = 'Skema Nilai';

    protected static ?int $navigationSort = 20;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTableCells;

    public static function form(Schema $schema): Schema
    {
        return GradeSchemaForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GradeSchemasTable::configure($table);
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
            'index' => ListGradeSchemas::route('/'),
            'create' => CreateGradeSchema::route('/create'),
            'edit' => EditGradeSchema::route('/{record}/edit'),
        ];
    }
}

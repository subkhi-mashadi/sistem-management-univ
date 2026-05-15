<?php

namespace App\Filament\Admin\Resources\GradeAppeals;

use App\Filament\Admin\Resources\GradeAppeals\Pages\CreateGradeAppeal;
use App\Filament\Admin\Resources\GradeAppeals\Pages\EditGradeAppeal;
use App\Filament\Admin\Resources\GradeAppeals\Pages\ListGradeAppeals;
use App\Filament\Admin\Resources\GradeAppeals\Schemas\GradeAppealForm;
use App\Filament\Admin\Resources\GradeAppeals\Tables\GradeAppealsTable;
use App\Models\GradeAppeal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class GradeAppealResource extends Resource
{
    protected static ?string $model = GradeAppeal::class;

    protected static string | \UnitEnum | null $navigationGroup = 'KRS & Nilai';

    protected static ?string $navigationLabel = 'Sanggah Nilai';

    protected static ?string $modelLabel = 'Sanggah Nilai';

    protected static ?string $pluralModelLabel = 'Sanggah Nilai';

    protected static ?int $navigationSort = 44;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedShieldExclamation;

    public static function form(Schema $schema): Schema
    {
        return GradeAppealForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return GradeAppealsTable::configure($table);
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
            'index' => ListGradeAppeals::route('/'),
            'create' => CreateGradeAppeal::route('/create'),
            'edit' => EditGradeAppeal::route('/{record}/edit'),
        ];
    }
}

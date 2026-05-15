<?php

namespace App\Filament\Admin\Resources\Fines;

use App\Filament\Admin\Resources\Fines\Pages\CreateFine;
use App\Filament\Admin\Resources\Fines\Pages\EditFine;
use App\Filament\Admin\Resources\Fines\Pages\ListFines;
use App\Filament\Admin\Resources\Fines\Schemas\FineForm;
use App\Filament\Admin\Resources\Fines\Tables\FinesTable;
use App\Models\Fine;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class FineResource extends Resource
{
    protected static ?string $model = Fine::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Denda';

    protected static ?string $modelLabel = 'Denda';

    protected static ?string $pluralModelLabel = 'Denda';

    protected static ?int $navigationSort = 58;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedExclamationTriangle;

    public static function form(Schema $schema): Schema
    {
        return FineForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return FinesTable::configure($table);
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
            'index' => ListFines::route('/'),
            'create' => CreateFine::route('/create'),
            'edit' => EditFine::route('/{record}/edit'),
        ];
    }
}

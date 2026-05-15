<?php

namespace App\Filament\Admin\Resources\TaxCalculations;

use App\Filament\Admin\Resources\TaxCalculations\Pages\CreateTaxCalculation;
use App\Filament\Admin\Resources\TaxCalculations\Pages\EditTaxCalculation;
use App\Filament\Admin\Resources\TaxCalculations\Pages\ListTaxCalculations;
use App\Filament\Admin\Resources\TaxCalculations\Schemas\TaxCalculationForm;
use App\Filament\Admin\Resources\TaxCalculations\Tables\TaxCalculationsTable;
use App\Models\TaxCalculation;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TaxCalculationResource extends Resource
{
    protected static ?string $model = TaxCalculation::class;

    protected static string | \UnitEnum | null $navigationGroup = 'SDM & Payroll';

    protected static ?string $navigationLabel = 'Kalkulasi Pajak';

    protected static ?string $modelLabel = 'Kalkulasi Pajak';

    protected static ?string $pluralModelLabel = 'Kalkulasi Pajak';

    protected static ?int $navigationSort = 99;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;

    public static function form(Schema $schema): Schema
    {
        return TaxCalculationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TaxCalculationsTable::configure($table);
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
            'index' => ListTaxCalculations::route('/'),
            'create' => CreateTaxCalculation::route('/create'),
            'edit' => EditTaxCalculation::route('/{record}/edit'),
        ];
    }
}

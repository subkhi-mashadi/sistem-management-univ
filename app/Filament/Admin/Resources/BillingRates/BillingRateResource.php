<?php

namespace App\Filament\Admin\Resources\BillingRates;

use App\Filament\Admin\Resources\BillingRates\Pages\CreateBillingRate;
use App\Filament\Admin\Resources\BillingRates\Pages\EditBillingRate;
use App\Filament\Admin\Resources\BillingRates\Pages\ListBillingRates;
use App\Filament\Admin\Resources\BillingRates\Schemas\BillingRateForm;
use App\Filament\Admin\Resources\BillingRates\Tables\BillingRatesTable;
use App\Models\BillingRate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BillingRateResource extends Resource
{
    protected static ?string $model = BillingRate::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Tarif Tagihan';

    protected static ?string $modelLabel = 'Tarif Tagihan';

    protected static ?string $pluralModelLabel = 'Tarif Tagihan';

    protected static ?int $navigationSort = 51;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalculator;

    public static function form(Schema $schema): Schema
    {
        return BillingRateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BillingRatesTable::configure($table);
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
            'index' => ListBillingRates::route('/'),
            'create' => CreateBillingRate::route('/create'),
            'edit' => EditBillingRate::route('/{record}/edit'),
        ];
    }
}

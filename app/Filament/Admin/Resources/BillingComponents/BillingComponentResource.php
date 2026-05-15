<?php

namespace App\Filament\Admin\Resources\BillingComponents;

use App\Filament\Admin\Resources\BillingComponents\Pages\CreateBillingComponent;
use App\Filament\Admin\Resources\BillingComponents\Pages\EditBillingComponent;
use App\Filament\Admin\Resources\BillingComponents\Pages\ListBillingComponents;
use App\Filament\Admin\Resources\BillingComponents\Schemas\BillingComponentForm;
use App\Filament\Admin\Resources\BillingComponents\Tables\BillingComponentsTable;
use App\Models\BillingComponent;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BillingComponentResource extends Resource
{
    protected static ?string $model = BillingComponent::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Komponen Tagihan';

    protected static ?string $modelLabel = 'Komponen Tagihan';

    protected static ?string $pluralModelLabel = 'Komponen Tagihan';

    protected static ?int $navigationSort = 50;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;

    public static function form(Schema $schema): Schema
    {
        return BillingComponentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BillingComponentsTable::configure($table);
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
            'index' => ListBillingComponents::route('/'),
            'create' => CreateBillingComponent::route('/create'),
            'edit' => EditBillingComponent::route('/{record}/edit'),
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

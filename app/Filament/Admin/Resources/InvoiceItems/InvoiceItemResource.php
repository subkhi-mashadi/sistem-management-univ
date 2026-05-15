<?php

namespace App\Filament\Admin\Resources\InvoiceItems;

use App\Filament\Admin\Resources\InvoiceItems\Pages\CreateInvoiceItem;
use App\Filament\Admin\Resources\InvoiceItems\Pages\EditInvoiceItem;
use App\Filament\Admin\Resources\InvoiceItems\Pages\ListInvoiceItems;
use App\Filament\Admin\Resources\InvoiceItems\Schemas\InvoiceItemForm;
use App\Filament\Admin\Resources\InvoiceItems\Tables\InvoiceItemsTable;
use App\Models\InvoiceItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class InvoiceItemResource extends Resource
{
    protected static ?string $model = InvoiceItem::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Item Invoice';

    protected static ?string $modelLabel = 'Item Invoice';

    protected static ?string $pluralModelLabel = 'Item Invoice';

    protected static ?int $navigationSort = 53;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    public static function form(Schema $schema): Schema
    {
        return InvoiceItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return InvoiceItemsTable::configure($table);
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
            'index' => ListInvoiceItems::route('/'),
            'create' => CreateInvoiceItem::route('/create'),
            'edit' => EditInvoiceItem::route('/{record}/edit'),
        ];
    }
}

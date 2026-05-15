<?php

namespace App\Filament\Admin\Resources\KrsItems;

use App\Filament\Admin\Resources\KrsItems\Pages\CreateKrsItem;
use App\Filament\Admin\Resources\KrsItems\Pages\EditKrsItem;
use App\Filament\Admin\Resources\KrsItems\Pages\ListKrsItems;
use App\Filament\Admin\Resources\KrsItems\Schemas\KrsItemForm;
use App\Filament\Admin\Resources\KrsItems\Tables\KrsItemsTable;
use App\Models\KrsItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class KrsItemResource extends Resource
{
    protected static ?string $model = KrsItem::class;

    protected static string | \UnitEnum | null $navigationGroup = 'KRS & Nilai';

    protected static ?string $navigationLabel = 'Item KRS';

    protected static ?string $modelLabel = 'Item KRS';

    protected static ?string $pluralModelLabel = 'Item KRS';

    protected static ?int $navigationSort = 41;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedQueueList;

    public static function form(Schema $schema): Schema
    {
        return KrsItemForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KrsItemsTable::configure($table);
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
            'index' => ListKrsItems::route('/'),
            'create' => CreateKrsItem::route('/create'),
            'edit' => EditKrsItem::route('/{record}/edit'),
        ];
    }
}

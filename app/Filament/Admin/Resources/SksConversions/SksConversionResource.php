<?php

namespace App\Filament\Admin\Resources\SksConversions;

use App\Filament\Admin\Resources\SksConversions\Pages\CreateSksConversion;
use App\Filament\Admin\Resources\SksConversions\Pages\EditSksConversion;
use App\Filament\Admin\Resources\SksConversions\Pages\ListSksConversions;
use App\Filament\Admin\Resources\SksConversions\Schemas\SksConversionForm;
use App\Filament\Admin\Resources\SksConversions\Tables\SksConversionsTable;
use App\Models\SksConversion;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SksConversionResource extends Resource
{
    protected static ?string $model = SksConversion::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Tugas Akhir & MBKM';

    protected static ?string $navigationLabel = 'Konversi SKS';

    protected static ?string $modelLabel = 'Konversi SKS';

    protected static ?string $pluralModelLabel = 'Konversi SKS';

    protected static ?int $navigationSort = 76;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowsRightLeft;

    public static function form(Schema $schema): Schema
    {
        return SksConversionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SksConversionsTable::configure($table);
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
            'index' => ListSksConversions::route('/'),
            'create' => CreateSksConversion::route('/create'),
            'edit' => EditSksConversion::route('/{record}/edit'),
        ];
    }
}

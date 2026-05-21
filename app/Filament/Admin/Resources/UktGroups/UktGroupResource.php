<?php

namespace App\Filament\Admin\Resources\UktGroups;

use App\Filament\Admin\Resources\UktGroups\Pages\CreateUktGroup;
use App\Filament\Admin\Resources\UktGroups\Pages\EditUktGroup;
use App\Filament\Admin\Resources\UktGroups\Pages\ListUktGroups;
use App\Filament\Admin\Resources\UktGroups\Schemas\UktGroupForm;
use App\Filament\Admin\Resources\UktGroups\Tables\UktGroupsTable;
use App\Models\UktGroup;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class UktGroupResource extends Resource
{
    protected static ?string $model = UktGroup::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    protected static ?string $navigationLabel = 'Golongan UKT';

    protected static ?string $modelLabel = 'Golongan UKT';

    protected static ?string $pluralModelLabel = 'Golongan UKT';

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';

    public static function form(Schema $schema): Schema
    {
        return UktGroupForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UktGroupsTable::configure($table);
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
            'index' => ListUktGroups::route('/'),
            'create' => CreateUktGroup::route('/create'),
            'edit' => EditUktGroup::route('/{record}/edit'),
        ];
    }
}

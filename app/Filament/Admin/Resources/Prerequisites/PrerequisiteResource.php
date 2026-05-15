<?php

namespace App\Filament\Admin\Resources\Prerequisites;

use App\Filament\Admin\Resources\Prerequisites\Pages\CreatePrerequisite;
use App\Filament\Admin\Resources\Prerequisites\Pages\EditPrerequisite;
use App\Filament\Admin\Resources\Prerequisites\Pages\ListPrerequisites;
use App\Filament\Admin\Resources\Prerequisites\Schemas\PrerequisiteForm;
use App\Filament\Admin\Resources\Prerequisites\Tables\PrerequisitesTable;
use App\Models\Prerequisite;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PrerequisiteResource extends Resource
{
    protected static ?string $model = Prerequisite::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Prasyarat MK';

    protected static ?string $modelLabel = 'Prasyarat';

    protected static ?string $pluralModelLabel = 'Prasyarat';

    protected static ?int $navigationSort = 14;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    public static function form(Schema $schema): Schema
    {
        return PrerequisiteForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PrerequisitesTable::configure($table);
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
            'index' => ListPrerequisites::route('/'),
            'create' => CreatePrerequisite::route('/create'),
            'edit' => EditPrerequisite::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources\Logbooks;

use App\Filament\Admin\Resources\Logbooks\Pages\CreateLogbook;
use App\Filament\Admin\Resources\Logbooks\Pages\EditLogbook;
use App\Filament\Admin\Resources\Logbooks\Pages\ListLogbooks;
use App\Filament\Admin\Resources\Logbooks\Schemas\LogbookForm;
use App\Filament\Admin\Resources\Logbooks\Tables\LogbooksTable;
use App\Models\Logbook;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LogbookResource extends Resource
{
    protected static ?string $model = Logbook::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Tugas Akhir & MBKM';

    protected static ?string $navigationLabel = 'Logbook Bimbingan';

    protected static ?string $modelLabel = 'Logbook';

    protected static ?string $pluralModelLabel = 'Logbook';

    protected static ?int $navigationSort = 72;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    public static function form(Schema $schema): Schema
    {
        return LogbookForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LogbooksTable::configure($table);
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
            'index' => ListLogbooks::route('/'),
            'create' => CreateLogbook::route('/create'),
            'edit' => EditLogbook::route('/{record}/edit'),
        ];
    }
}

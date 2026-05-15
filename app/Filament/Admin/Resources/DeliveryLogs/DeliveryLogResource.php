<?php

namespace App\Filament\Admin\Resources\DeliveryLogs;

use App\Filament\Admin\Resources\DeliveryLogs\Pages\CreateDeliveryLog;
use App\Filament\Admin\Resources\DeliveryLogs\Pages\EditDeliveryLog;
use App\Filament\Admin\Resources\DeliveryLogs\Pages\ListDeliveryLogs;
use App\Filament\Admin\Resources\DeliveryLogs\Schemas\DeliveryLogForm;
use App\Filament\Admin\Resources\DeliveryLogs\Tables\DeliveryLogsTable;
use App\Models\DeliveryLog;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DeliveryLogResource extends Resource
{
    protected static ?string $model = DeliveryLog::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Komunikasi';

    protected static ?string $navigationLabel = 'Log Pengiriman';

    protected static ?string $modelLabel = 'Log Pengiriman';

    protected static ?string $pluralModelLabel = 'Log Pengiriman';

    protected static ?int $navigationSort = 84;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPaperAirplane;

    public static function form(Schema $schema): Schema
    {
        return DeliveryLogForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DeliveryLogsTable::configure($table);
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
            'index' => ListDeliveryLogs::route('/'),
            'create' => CreateDeliveryLog::route('/create'),
            'edit' => EditDeliveryLog::route('/{record}/edit'),
        ];
    }
}

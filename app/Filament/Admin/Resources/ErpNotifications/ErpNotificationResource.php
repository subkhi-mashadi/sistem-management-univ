<?php

namespace App\Filament\Admin\Resources\ErpNotifications;

use App\Filament\Admin\Resources\ErpNotifications\Pages\CreateErpNotification;
use App\Filament\Admin\Resources\ErpNotifications\Pages\EditErpNotification;
use App\Filament\Admin\Resources\ErpNotifications\Pages\ListErpNotifications;
use App\Filament\Admin\Resources\ErpNotifications\Schemas\ErpNotificationForm;
use App\Filament\Admin\Resources\ErpNotifications\Tables\ErpNotificationsTable;
use App\Models\ErpNotification;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ErpNotificationResource extends Resource
{
    protected static ?string $model = ErpNotification::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Komunikasi';

    protected static ?string $navigationLabel = 'Notifikasi';

    protected static ?string $modelLabel = 'Notifikasi';

    protected static ?string $pluralModelLabel = 'Notifikasi';

    protected static ?int $navigationSort = 82;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBell;

    public static function form(Schema $schema): Schema
    {
        return ErpNotificationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ErpNotificationsTable::configure($table);
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
            'index' => ListErpNotifications::route('/'),
            'create' => CreateErpNotification::route('/create'),
            'edit' => EditErpNotification::route('/{record}/edit'),
        ];
    }
}

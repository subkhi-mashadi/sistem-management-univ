<?php

namespace App\Filament\Admin\Resources\NotificationTemplates;

use App\Filament\Admin\Resources\NotificationTemplates\Pages\CreateNotificationTemplate;
use App\Filament\Admin\Resources\NotificationTemplates\Pages\EditNotificationTemplate;
use App\Filament\Admin\Resources\NotificationTemplates\Pages\ListNotificationTemplates;
use App\Filament\Admin\Resources\NotificationTemplates\Schemas\NotificationTemplateForm;
use App\Filament\Admin\Resources\NotificationTemplates\Tables\NotificationTemplatesTable;
use App\Models\NotificationTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class NotificationTemplateResource extends Resource
{
    protected static ?string $model = NotificationTemplate::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Komunikasi';

    protected static ?string $navigationLabel = 'Template Notifikasi';

    protected static ?string $modelLabel = 'Template Notifikasi';

    protected static ?string $pluralModelLabel = 'Template Notifikasi';

    protected static ?int $navigationSort = 80;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    public static function form(Schema $schema): Schema
    {
        return NotificationTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return NotificationTemplatesTable::configure($table);
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
            'index' => ListNotificationTemplates::route('/'),
            'create' => CreateNotificationTemplate::route('/create'),
            'edit' => EditNotificationTemplate::route('/{record}/edit'),
        ];
    }
}

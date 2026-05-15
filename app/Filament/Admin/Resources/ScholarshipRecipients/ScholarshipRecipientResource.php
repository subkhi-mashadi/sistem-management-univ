<?php

namespace App\Filament\Admin\Resources\ScholarshipRecipients;

use App\Filament\Admin\Resources\ScholarshipRecipients\Pages\CreateScholarshipRecipient;
use App\Filament\Admin\Resources\ScholarshipRecipients\Pages\EditScholarshipRecipient;
use App\Filament\Admin\Resources\ScholarshipRecipients\Pages\ListScholarshipRecipients;
use App\Filament\Admin\Resources\ScholarshipRecipients\Schemas\ScholarshipRecipientForm;
use App\Filament\Admin\Resources\ScholarshipRecipients\Tables\ScholarshipRecipientsTable;
use App\Models\ScholarshipRecipient;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ScholarshipRecipientResource extends Resource
{
    protected static ?string $model = ScholarshipRecipient::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Penerima Beasiswa';

    protected static ?string $modelLabel = 'Penerima Beasiswa';

    protected static ?string $pluralModelLabel = 'Penerima Beasiswa';

    protected static ?int $navigationSort = 56;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return ScholarshipRecipientForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScholarshipRecipientsTable::configure($table);
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
            'index' => ListScholarshipRecipients::route('/'),
            'create' => CreateScholarshipRecipient::route('/create'),
            'edit' => EditScholarshipRecipient::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources\LetterArchives;

use App\Filament\Admin\Resources\LetterArchives\Pages\CreateLetterArchive;
use App\Filament\Admin\Resources\LetterArchives\Pages\EditLetterArchive;
use App\Filament\Admin\Resources\LetterArchives\Pages\ListLetterArchives;
use App\Filament\Admin\Resources\LetterArchives\Schemas\LetterArchiveForm;
use App\Filament\Admin\Resources\LetterArchives\Tables\LetterArchivesTable;
use App\Models\LetterArchive;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class LetterArchiveResource extends Resource
{
    protected static ?string $model = LetterArchive::class;

    protected static string|\UnitEnum|null $navigationGroup = 'E-Office';

    protected static ?string $navigationLabel = 'Arsip Surat';

    protected static ?string $modelLabel = 'Arsip Surat';

    protected static ?string $pluralModelLabel = 'Arsip Surat';

    protected static ?int $navigationSort = 66;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArchiveBox;

    public static function form(Schema $schema): Schema
    {
        return LetterArchiveForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LetterArchivesTable::configure($table);
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
            'index' => ListLetterArchives::route('/'),
            'create' => CreateLetterArchive::route('/create'),
            'edit' => EditLetterArchive::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Student\Resources\Logbooks;

use App\Filament\Student\Resources\Logbooks\Pages\CreateLogbook;
use App\Filament\Student\Resources\Logbooks\Pages\ListLogbooks;
use App\Filament\Student\Resources\Logbooks\Pages\ViewLogbook;
use App\Filament\Student\Resources\Logbooks\Schemas\LogbookForm;
use App\Filament\Student\Resources\Logbooks\Tables\LogbooksTable;
use App\Models\Logbook;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LogbookResource extends Resource
{
    protected static ?string $model = Logbook::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    protected static ?string $navigationLabel = 'Logbook Bimbingan';

    protected static ?string $modelLabel = 'Logbook Bimbingan';

    protected static ?string $pluralModelLabel = 'Logbook Bimbingan';

    protected static ?int $navigationSort = 6;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('student_id', auth()->user()?->student?->id);
    }

    public static function form(Schema $schema): Schema
    {
        return LogbookForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LogbooksTable::configure($table);
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLogbooks::route('/'),
            'create' => CreateLogbook::route('/create'),
            'view' => ViewLogbook::route('/{record}'),
        ];
    }
}

<?php

namespace App\Filament\Student\Resources\LetterRequests;

use App\Filament\Student\Resources\LetterRequests\Pages\CreateLetterRequest;
use App\Filament\Student\Resources\LetterRequests\Pages\EditLetterRequest;
use App\Filament\Student\Resources\LetterRequests\Pages\ListLetterRequests;
use App\Filament\Student\Resources\LetterRequests\Pages\ViewLetterRequest;
use App\Filament\Student\Resources\LetterRequests\Schemas\LetterRequestForm;
use App\Filament\Student\Resources\LetterRequests\Tables\LetterRequestsTable;
use App\Models\LetterRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class LetterRequestResource extends Resource
{
    protected static ?string $model = LetterRequest::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Pengajuan Surat';

    protected static ?string $modelLabel = 'Pengajuan Surat';

    protected static ?string $pluralModelLabel = 'Pengajuan Surat';

    protected static ?int $navigationSort = 3;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('requester_id', auth()->id());
    }

    public static function form(Schema $schema): Schema
    {
        return LetterRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LetterRequestsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListLetterRequests::route('/'),
            'create' => CreateLetterRequest::route('/create'),
            'edit' => EditLetterRequest::route('/{record}/edit'),
            'view' => ViewLetterRequest::route('/{record}'),
        ];
    }
}

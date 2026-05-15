<?php

namespace App\Filament\Admin\Resources\LetterRequests;

use App\Filament\Admin\Resources\LetterRequests\Pages\CreateLetterRequest;
use App\Filament\Admin\Resources\LetterRequests\Pages\EditLetterRequest;
use App\Filament\Admin\Resources\LetterRequests\Pages\ListLetterRequests;
use App\Filament\Admin\Resources\LetterRequests\Schemas\LetterRequestForm;
use App\Filament\Admin\Resources\LetterRequests\Tables\LetterRequestsTable;
use App\Models\LetterRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LetterRequestResource extends Resource
{
    protected static ?string $model = LetterRequest::class;

    protected static string | \UnitEnum | null $navigationGroup = 'E-Office';

    protected static ?string $navigationLabel = 'Pengajuan Surat';

    protected static ?string $modelLabel = 'Pengajuan Surat';

    protected static ?string $pluralModelLabel = 'Pengajuan Surat';

    protected static ?int $navigationSort = 63;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    public static function form(Schema $schema): Schema
    {
        return LetterRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LetterRequestsTable::configure($table);
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
            'index' => ListLetterRequests::route('/'),
            'create' => CreateLetterRequest::route('/create'),
            'edit' => EditLetterRequest::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}

<?php

namespace App\Filament\Admin\Resources\ESignatures;

use App\Filament\Admin\Resources\ESignatures\Pages\CreateESignature;
use App\Filament\Admin\Resources\ESignatures\Pages\EditESignature;
use App\Filament\Admin\Resources\ESignatures\Pages\ListESignatures;
use App\Filament\Admin\Resources\ESignatures\Schemas\ESignatureForm;
use App\Filament\Admin\Resources\ESignatures\Tables\ESignaturesTable;
use App\Models\ESignature;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ESignatureResource extends Resource
{
    protected static ?string $model = ESignature::class;

    protected static string|\UnitEnum|null $navigationGroup = 'E-Office';

    protected static ?string $navigationLabel = 'E-Signature';

    protected static ?string $modelLabel = 'E-Signature';

    protected static ?string $pluralModelLabel = 'E-Signature';

    protected static ?int $navigationSort = 65;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedPencilSquare;

    public static function form(Schema $schema): Schema
    {
        return ESignatureForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ESignaturesTable::configure($table);
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
            'index' => ListESignatures::route('/'),
            'create' => CreateESignature::route('/create'),
            'edit' => EditESignature::route('/{record}/edit'),
        ];
    }
}

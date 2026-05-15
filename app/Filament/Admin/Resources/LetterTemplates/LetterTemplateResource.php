<?php

namespace App\Filament\Admin\Resources\LetterTemplates;

use App\Filament\Admin\Resources\LetterTemplates\Pages\CreateLetterTemplate;
use App\Filament\Admin\Resources\LetterTemplates\Pages\EditLetterTemplate;
use App\Filament\Admin\Resources\LetterTemplates\Pages\ListLetterTemplates;
use App\Filament\Admin\Resources\LetterTemplates\Schemas\LetterTemplateForm;
use App\Filament\Admin\Resources\LetterTemplates\Tables\LetterTemplatesTable;
use App\Models\LetterTemplate;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class LetterTemplateResource extends Resource
{
    protected static ?string $model = LetterTemplate::class;

    protected static string | \UnitEnum | null $navigationGroup = 'E-Office';

    protected static ?string $navigationLabel = 'Template Surat';

    protected static ?string $modelLabel = 'Template Surat';

    protected static ?string $pluralModelLabel = 'Template Surat';

    protected static ?int $navigationSort = 60;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    public static function form(Schema $schema): Schema
    {
        return LetterTemplateForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return LetterTemplatesTable::configure($table);
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
            'index' => ListLetterTemplates::route('/'),
            'create' => CreateLetterTemplate::route('/create'),
            'edit' => EditLetterTemplate::route('/{record}/edit'),
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

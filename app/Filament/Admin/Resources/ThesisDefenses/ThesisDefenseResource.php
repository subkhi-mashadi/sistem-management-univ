<?php

namespace App\Filament\Admin\Resources\ThesisDefenses;

use App\Filament\Admin\Resources\ThesisDefenses\Pages\CreateThesisDefense;
use App\Filament\Admin\Resources\ThesisDefenses\Pages\EditThesisDefense;
use App\Filament\Admin\Resources\ThesisDefenses\Pages\ListThesisDefenses;
use App\Filament\Admin\Resources\ThesisDefenses\Schemas\ThesisDefenseForm;
use App\Filament\Admin\Resources\ThesisDefenses\Tables\ThesisDefensesTable;
use App\Models\ThesisDefense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThesisDefenseResource extends Resource
{
    protected static ?string $model = ThesisDefense::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Tugas Akhir & MBKM';

    protected static ?string $navigationLabel = 'Sidang Skripsi';

    protected static ?string $modelLabel = 'Sidang Skripsi';

    protected static ?string $pluralModelLabel = 'Sidang Skripsi';

    protected static ?int $navigationSort = 73;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return ThesisDefenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThesisDefensesTable::configure($table);
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
            'index' => ListThesisDefenses::route('/'),
            'create' => CreateThesisDefense::route('/create'),
            'edit' => EditThesisDefense::route('/{record}/edit'),
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

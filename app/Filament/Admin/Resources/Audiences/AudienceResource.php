<?php

namespace App\Filament\Admin\Resources\Audiences;

use App\Filament\Admin\Resources\Audiences\Pages\CreateAudience;
use App\Filament\Admin\Resources\Audiences\Pages\EditAudience;
use App\Filament\Admin\Resources\Audiences\Pages\ListAudiences;
use App\Filament\Admin\Resources\Audiences\Schemas\AudienceForm;
use App\Filament\Admin\Resources\Audiences\Tables\AudiencesTable;
use App\Models\Audience;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AudienceResource extends Resource
{
    protected static ?string $model = Audience::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Komunikasi';

    protected static ?string $navigationLabel = 'Segmen Audiens';

    protected static ?string $modelLabel = 'Audiens';

    protected static ?string $pluralModelLabel = 'Audiens';

    protected static ?int $navigationSort = 83;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    public static function form(Schema $schema): Schema
    {
        return AudienceForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AudiencesTable::configure($table);
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
            'index' => ListAudiences::route('/'),
            'create' => CreateAudience::route('/create'),
            'edit' => EditAudience::route('/{record}/edit'),
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

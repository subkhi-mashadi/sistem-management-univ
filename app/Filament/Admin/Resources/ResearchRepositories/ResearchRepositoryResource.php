<?php

namespace App\Filament\Admin\Resources\ResearchRepositories;

use App\Filament\Admin\Resources\ResearchRepositories\Pages\CreateResearchRepository;
use App\Filament\Admin\Resources\ResearchRepositories\Pages\EditResearchRepository;
use App\Filament\Admin\Resources\ResearchRepositories\Pages\ListResearchRepositories;
use App\Filament\Admin\Resources\ResearchRepositories\Schemas\ResearchRepositoryForm;
use App\Filament\Admin\Resources\ResearchRepositories\Tables\ResearchRepositoriesTable;
use App\Models\ResearchRepository;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ResearchRepositoryResource extends Resource
{
    protected static ?string $model = ResearchRepository::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Tugas Akhir & MBKM';

    protected static ?string $navigationLabel = 'Repository Riset';

    protected static ?string $modelLabel = 'Karya Ilmiah';

    protected static ?string $pluralModelLabel = 'Karya Ilmiah';

    protected static ?int $navigationSort = 77;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentDuplicate;

    public static function form(Schema $schema): Schema
    {
        return ResearchRepositoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ResearchRepositoriesTable::configure($table);
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
            'index' => ListResearchRepositories::route('/'),
            'create' => CreateResearchRepository::route('/create'),
            'edit' => EditResearchRepository::route('/{record}/edit'),
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

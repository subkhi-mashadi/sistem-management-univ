<?php

namespace App\Filament\Admin\Resources\ThesisTopics;

use App\Filament\Admin\Resources\ThesisTopics\Pages\CreateThesisTopic;
use App\Filament\Admin\Resources\ThesisTopics\Pages\EditThesisTopic;
use App\Filament\Admin\Resources\ThesisTopics\Pages\ListThesisTopics;
use App\Filament\Admin\Resources\ThesisTopics\Schemas\ThesisTopicForm;
use App\Filament\Admin\Resources\ThesisTopics\Tables\ThesisTopicsTable;
use App\Models\ThesisTopic;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ThesisTopicResource extends Resource
{
    protected static ?string $model = ThesisTopic::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Tugas Akhir & MBKM';

    protected static ?string $navigationLabel = 'Topik Skripsi';

    protected static ?string $modelLabel = 'Topik Skripsi';

    protected static ?string $pluralModelLabel = 'Topik Skripsi';

    protected static ?int $navigationSort = 70;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedLightBulb;

    public static function form(Schema $schema): Schema
    {
        return ThesisTopicForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThesisTopicsTable::configure($table);
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
            'index' => ListThesisTopics::route('/'),
            'create' => CreateThesisTopic::route('/create'),
            'edit' => EditThesisTopic::route('/{record}/edit'),
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

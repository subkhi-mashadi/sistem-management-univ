<?php

namespace App\Filament\Admin\Resources\Scholarships;

use App\Filament\Admin\Resources\Scholarships\Pages\CreateScholarship;
use App\Filament\Admin\Resources\Scholarships\Pages\EditScholarship;
use App\Filament\Admin\Resources\Scholarships\Pages\ListScholarships;
use App\Filament\Admin\Resources\Scholarships\Schemas\ScholarshipForm;
use App\Filament\Admin\Resources\Scholarships\Tables\ScholarshipsTable;
use App\Models\Scholarship;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ScholarshipResource extends Resource
{
    protected static ?string $model = Scholarship::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Beasiswa';

    protected static ?string $modelLabel = 'Beasiswa';

    protected static ?string $pluralModelLabel = 'Beasiswa';

    protected static ?int $navigationSort = 55;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    public static function form(Schema $schema): Schema
    {
        return ScholarshipForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ScholarshipsTable::configure($table);
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
            'index' => ListScholarships::route('/'),
            'create' => CreateScholarship::route('/create'),
            'edit' => EditScholarship::route('/{record}/edit'),
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

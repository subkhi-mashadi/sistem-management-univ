<?php

namespace App\Filament\Admin\Resources\CourseOfferings;

use App\Filament\Admin\Resources\CourseOfferings\Pages\CreateCourseOffering;
use App\Filament\Admin\Resources\CourseOfferings\Pages\EditCourseOffering;
use App\Filament\Admin\Resources\CourseOfferings\Pages\ListCourseOfferings;
use App\Filament\Admin\Resources\CourseOfferings\RelationManagers\GradesRelationManager;
use App\Filament\Admin\Resources\CourseOfferings\Schemas\CourseOfferingForm;
use App\Filament\Admin\Resources\CourseOfferings\Tables\CourseOfferingsTable;
use App\Models\CourseOffering;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CourseOfferingResource extends Resource
{
    protected static ?string $model = CourseOffering::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Penawaran MK';

    protected static ?string $modelLabel = 'Penawaran MK';

    protected static ?string $pluralModelLabel = 'Penawaran MK';

    protected static ?int $navigationSort = 21;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquares2x2;

    public static function form(Schema $schema): Schema
    {
        return CourseOfferingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CourseOfferingsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            GradesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCourseOfferings::route('/'),
            'create' => CreateCourseOffering::route('/create'),
            'edit' => EditCourseOffering::route('/{record}/edit'),
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

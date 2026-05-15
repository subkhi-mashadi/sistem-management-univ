<?php

namespace App\Filament\Admin\Resources\Refunds;

use App\Filament\Admin\Resources\Refunds\Pages\CreateRefund;
use App\Filament\Admin\Resources\Refunds\Pages\EditRefund;
use App\Filament\Admin\Resources\Refunds\Pages\ListRefunds;
use App\Filament\Admin\Resources\Refunds\Schemas\RefundForm;
use App\Filament\Admin\Resources\Refunds\Tables\RefundsTable;
use App\Models\Refund;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RefundResource extends Resource
{
    protected static ?string $model = Refund::class;

    protected static string | \UnitEnum | null $navigationGroup = 'Keuangan';

    protected static ?string $navigationLabel = 'Refund';

    protected static ?string $modelLabel = 'Refund';

    protected static ?string $pluralModelLabel = 'Refund';

    protected static ?int $navigationSort = 59;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedArrowUturnLeft;

    public static function form(Schema $schema): Schema
    {
        return RefundForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RefundsTable::configure($table);
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
            'index' => ListRefunds::route('/'),
            'create' => CreateRefund::route('/create'),
            'edit' => EditRefund::route('/{record}/edit'),
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

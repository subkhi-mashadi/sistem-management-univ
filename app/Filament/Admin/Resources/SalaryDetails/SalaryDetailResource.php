<?php

namespace App\Filament\Admin\Resources\SalaryDetails;

use App\Filament\Admin\Resources\SalaryDetails\Pages\CreateSalaryDetail;
use App\Filament\Admin\Resources\SalaryDetails\Pages\EditSalaryDetail;
use App\Filament\Admin\Resources\SalaryDetails\Pages\ListSalaryDetails;
use App\Filament\Admin\Resources\SalaryDetails\Schemas\SalaryDetailForm;
use App\Filament\Admin\Resources\SalaryDetails\Tables\SalaryDetailsTable;
use App\Models\SalaryDetail;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalaryDetailResource extends Resource
{
    protected static ?string $model = SalaryDetail::class;

    protected static string|\UnitEnum|null $navigationGroup = 'SDM & Payroll';

    protected static ?string $navigationLabel = 'Detail Gaji';

    protected static ?string $modelLabel = 'Detail Gaji';

    protected static ?string $pluralModelLabel = 'Detail Gaji';

    protected static ?int $navigationSort = 97;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedListBullet;

    public static function form(Schema $schema): Schema
    {
        return SalaryDetailForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalaryDetailsTable::configure($table);
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
            'index' => ListSalaryDetails::route('/'),
            'create' => CreateSalaryDetail::route('/create'),
            'edit' => EditSalaryDetail::route('/{record}/edit'),
        ];
    }
}

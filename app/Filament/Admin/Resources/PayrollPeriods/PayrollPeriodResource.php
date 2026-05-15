<?php

namespace App\Filament\Admin\Resources\PayrollPeriods;

use App\Filament\Admin\Resources\PayrollPeriods\Pages\CreatePayrollPeriod;
use App\Filament\Admin\Resources\PayrollPeriods\Pages\EditPayrollPeriod;
use App\Filament\Admin\Resources\PayrollPeriods\Pages\ListPayrollPeriods;
use App\Filament\Admin\Resources\PayrollPeriods\Schemas\PayrollPeriodForm;
use App\Filament\Admin\Resources\PayrollPeriods\Tables\PayrollPeriodsTable;
use App\Models\PayrollPeriod;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PayrollPeriodResource extends Resource
{
    protected static ?string $model = PayrollPeriod::class;

    protected static string|\UnitEnum|null $navigationGroup = 'SDM & Payroll';

    protected static ?string $navigationLabel = 'Periode Payroll';

    protected static ?string $modelLabel = 'Periode Payroll';

    protected static ?string $pluralModelLabel = 'Periode Payroll';

    protected static ?int $navigationSort = 94;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    public static function form(Schema $schema): Schema
    {
        return PayrollPeriodForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PayrollPeriodsTable::configure($table);
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
            'index' => ListPayrollPeriods::route('/'),
            'create' => CreatePayrollPeriod::route('/create'),
            'edit' => EditPayrollPeriod::route('/{record}/edit'),
        ];
    }
}

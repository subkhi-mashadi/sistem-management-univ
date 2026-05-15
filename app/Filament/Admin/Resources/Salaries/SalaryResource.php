<?php

namespace App\Filament\Admin\Resources\Salaries;

use App\Filament\Admin\Resources\Salaries\Pages\CreateSalary;
use App\Filament\Admin\Resources\Salaries\Pages\EditSalary;
use App\Filament\Admin\Resources\Salaries\Pages\ListSalaries;
use App\Filament\Admin\Resources\Salaries\Schemas\SalaryForm;
use App\Filament\Admin\Resources\Salaries\Tables\SalariesTable;
use App\Models\Salary;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class SalaryResource extends Resource
{
    protected static ?string $model = Salary::class;

    protected static string|\UnitEnum|null $navigationGroup = 'SDM & Payroll';

    protected static ?string $navigationLabel = 'Gaji';

    protected static ?string $modelLabel = 'Gaji';

    protected static ?string $pluralModelLabel = 'Gaji';

    protected static ?int $navigationSort = 96;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return SalaryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return SalariesTable::configure($table);
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
            'index' => ListSalaries::route('/'),
            'create' => CreateSalary::route('/create'),
            'edit' => EditSalary::route('/{record}/edit'),
        ];
    }
}

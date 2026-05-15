<?php

namespace App\Filament\Admin\Resources\EmploymentHistories;

use App\Filament\Admin\Resources\EmploymentHistories\Pages\CreateEmploymentHistory;
use App\Filament\Admin\Resources\EmploymentHistories\Pages\EditEmploymentHistory;
use App\Filament\Admin\Resources\EmploymentHistories\Pages\ListEmploymentHistories;
use App\Filament\Admin\Resources\EmploymentHistories\Schemas\EmploymentHistoryForm;
use App\Filament\Admin\Resources\EmploymentHistories\Tables\EmploymentHistoriesTable;
use App\Models\EmploymentHistory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class EmploymentHistoryResource extends Resource
{
    protected static ?string $model = EmploymentHistory::class;

    protected static string|\UnitEnum|null $navigationGroup = 'SDM & Payroll';

    protected static ?string $navigationLabel = 'Riwayat Kepegawaian';

    protected static ?string $modelLabel = 'Riwayat';

    protected static ?string $pluralModelLabel = 'Riwayat';

    protected static ?int $navigationSort = 91;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClock;

    public static function form(Schema $schema): Schema
    {
        return EmploymentHistoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EmploymentHistoriesTable::configure($table);
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
            'index' => ListEmploymentHistories::route('/'),
            'create' => CreateEmploymentHistory::route('/create'),
            'edit' => EditEmploymentHistory::route('/{record}/edit'),
        ];
    }
}

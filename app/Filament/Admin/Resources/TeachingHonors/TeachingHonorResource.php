<?php

namespace App\Filament\Admin\Resources\TeachingHonors;

use App\Filament\Admin\Resources\TeachingHonors\Pages\CreateTeachingHonor;
use App\Filament\Admin\Resources\TeachingHonors\Pages\EditTeachingHonor;
use App\Filament\Admin\Resources\TeachingHonors\Pages\ListTeachingHonors;
use App\Filament\Admin\Resources\TeachingHonors\Schemas\TeachingHonorForm;
use App\Filament\Admin\Resources\TeachingHonors\Tables\TeachingHonorsTable;
use App\Models\TeachingHonor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TeachingHonorResource extends Resource
{
    protected static ?string $model = TeachingHonor::class;

    protected static string|\UnitEnum|null $navigationGroup = 'SDM & Payroll';

    protected static ?string $navigationLabel = 'Honor Mengajar';

    protected static ?string $modelLabel = 'Honor Mengajar';

    protected static ?string $pluralModelLabel = 'Honor Mengajar';

    protected static ?int $navigationSort = 98;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;

    public static function form(Schema $schema): Schema
    {
        return TeachingHonorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TeachingHonorsTable::configure($table);
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
            'index' => ListTeachingHonors::route('/'),
            'create' => CreateTeachingHonor::route('/create'),
            'edit' => EditTeachingHonor::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Admin\Resources\ThesisAdvisors;

use App\Filament\Admin\Resources\ThesisAdvisors\Pages\CreateThesisAdvisor;
use App\Filament\Admin\Resources\ThesisAdvisors\Pages\EditThesisAdvisor;
use App\Filament\Admin\Resources\ThesisAdvisors\Pages\ListThesisAdvisors;
use App\Filament\Admin\Resources\ThesisAdvisors\Schemas\ThesisAdvisorForm;
use App\Filament\Admin\Resources\ThesisAdvisors\Tables\ThesisAdvisorsTable;
use App\Models\ThesisAdvisor;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ThesisAdvisorResource extends Resource
{
    protected static ?string $model = ThesisAdvisor::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Tugas Akhir & MBKM';

    protected static ?string $navigationLabel = 'Pembimbing Skripsi';

    protected static ?string $modelLabel = 'Pembimbing Skripsi';

    protected static ?string $pluralModelLabel = 'Pembimbing Skripsi';

    protected static ?int $navigationSort = 71;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserPlus;

    public static function form(Schema $schema): Schema
    {
        return ThesisAdvisorForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ThesisAdvisorsTable::configure($table);
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
            'index' => ListThesisAdvisors::route('/'),
            'create' => CreateThesisAdvisor::route('/create'),
            'edit' => EditThesisAdvisor::route('/{record}/edit'),
        ];
    }
}

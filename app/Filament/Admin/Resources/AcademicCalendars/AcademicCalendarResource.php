<?php

namespace App\Filament\Admin\Resources\AcademicCalendars;

use App\Filament\Admin\Resources\AcademicCalendars\Pages\CreateAcademicCalendar;
use App\Filament\Admin\Resources\AcademicCalendars\Pages\EditAcademicCalendar;
use App\Filament\Admin\Resources\AcademicCalendars\Pages\ListAcademicCalendars;
use App\Filament\Admin\Resources\AcademicCalendars\Schemas\AcademicCalendarForm;
use App\Filament\Admin\Resources\AcademicCalendars\Tables\AcademicCalendarsTable;
use App\Models\AcademicCalendar;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AcademicCalendarResource extends Resource
{
    protected static ?string $model = AcademicCalendar::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Akademik';

    protected static ?string $navigationLabel = 'Kalender Akademik';

    protected static ?string $modelLabel = 'Kalender Akademik';

    protected static ?string $pluralModelLabel = 'Kalender Akademik';

    protected static ?int $navigationSort = 18;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCalendar;

    public static function form(Schema $schema): Schema
    {
        return AcademicCalendarForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AcademicCalendarsTable::configure($table);
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
            'index' => ListAcademicCalendars::route('/'),
            'create' => CreateAcademicCalendar::route('/create'),
            'edit' => EditAcademicCalendar::route('/{record}/edit'),
        ];
    }
}

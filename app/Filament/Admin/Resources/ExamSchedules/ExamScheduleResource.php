<?php

namespace App\Filament\Admin\Resources\ExamSchedules;

use App\Filament\Admin\Resources\ExamSchedules\Pages\CreateExamSchedule;
use App\Filament\Admin\Resources\ExamSchedules\Pages\EditExamSchedule;
use App\Filament\Admin\Resources\ExamSchedules\Pages\ListExamSchedules;
use App\Filament\Admin\Resources\ExamSchedules\Schemas\ExamScheduleForm;
use App\Filament\Admin\Resources\ExamSchedules\Tables\ExamSchedulesTable;
use App\Models\ExamSchedule;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ExamScheduleResource extends Resource
{
    protected static ?string $model = ExamSchedule::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Penjadwalan';

    protected static ?string $navigationLabel = 'Jadwal Ujian';

    protected static ?string $modelLabel = 'Jadwal Ujian';

    protected static ?string $pluralModelLabel = 'Jadwal Ujian';

    protected static ?int $navigationSort = 34;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    public static function form(Schema $schema): Schema
    {
        return ExamScheduleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ExamSchedulesTable::configure($table);
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
            'index' => ListExamSchedules::route('/'),
            'create' => CreateExamSchedule::route('/create'),
            'edit' => EditExamSchedule::route('/{record}/edit'),
        ];
    }
}

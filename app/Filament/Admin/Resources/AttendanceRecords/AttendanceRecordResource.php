<?php

namespace App\Filament\Admin\Resources\AttendanceRecords;

use App\Filament\Admin\Resources\AttendanceRecords\Pages\CreateAttendanceRecord;
use App\Filament\Admin\Resources\AttendanceRecords\Pages\EditAttendanceRecord;
use App\Filament\Admin\Resources\AttendanceRecords\Pages\ListAttendanceRecords;
use App\Filament\Admin\Resources\AttendanceRecords\Schemas\AttendanceRecordForm;
use App\Filament\Admin\Resources\AttendanceRecords\Tables\AttendanceRecordsTable;
use App\Models\AttendanceRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AttendanceRecordResource extends Resource
{
    protected static ?string $model = AttendanceRecord::class;

    protected static string|\UnitEnum|null $navigationGroup = 'SDM & Payroll';

    protected static ?string $navigationLabel = 'Presensi Pegawai';

    protected static ?string $modelLabel = 'Presensi';

    protected static ?string $pluralModelLabel = 'Presensi';

    protected static ?int $navigationSort = 92;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCheckCircle;

    public static function form(Schema $schema): Schema
    {
        return AttendanceRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AttendanceRecordsTable::configure($table);
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
            'index' => ListAttendanceRecords::route('/'),
            'create' => CreateAttendanceRecord::route('/create'),
            'edit' => EditAttendanceRecord::route('/{record}/edit'),
        ];
    }
}

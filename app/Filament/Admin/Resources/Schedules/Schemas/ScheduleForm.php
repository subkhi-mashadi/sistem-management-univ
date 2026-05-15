<?php

namespace App\Filament\Admin\Resources\Schedules\Schemas;

use App\Enums\Scheduling\DayOfWeek;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_offering_id')
                    ->label('Penawaran MK')
                    ->relationship('courseOffering', 'id')
                    ->required(),
                Select::make('classroom_id')
                    ->label('Ruang Kelas')
                    ->relationship('classroom', 'name'),
                Select::make('day_of_week')
                    ->label('Hari')
                    ->options(DayOfWeek::class)
                    ->required(),
                TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->required(),
                TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->required(),
                TextInput::make('meeting_count')
                    ->label('Jumlah Pertemuan')
                    ->required()
                    ->numeric()
                    ->default(14),
            ]);
    }
}

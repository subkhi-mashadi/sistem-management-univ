<?php

namespace App\Filament\Admin\Resources\ExamSchedules\Schemas;

use App\Enums\Scheduling\ExamType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Schema;

class ExamScheduleForm
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
                Select::make('exam_type')
                    ->label('Jenis Ujian')
                    ->options(ExamType::class)
                    ->required(),
                DatePicker::make('exam_date')
                    ->label('Tanggal Ujian')
                    ->required(),
                TimePicker::make('start_time')
                    ->label('Jam Mulai')
                    ->required(),
                TimePicker::make('end_time')
                    ->label('Jam Selesai')
                    ->required(),
                TextInput::make('proctor_ids')
                    ->label('Pengawas'),
            ]);
    }
}

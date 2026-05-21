<?php

namespace App\Filament\Admin\Resources\Schedules\Schemas;

use App\Enums\Scheduling\DayOfWeek;
use App\Models\CourseOffering;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ScheduleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('course_offering_id')
                            ->label('Penawaran MK')
                            ->options(fn () => CourseOffering::query()
                                ->with(['course:id,code,name', 'semester:id,name'])
                                ->whereHas('semester', fn ($q) => $q->where('is_active', true))
                                ->get()
                                ->mapWithKeys(fn (CourseOffering $o) => [
                                    $o->id => ($o->course?->code ?? '?').' — '.($o->course?->name ?? 'MK #'.$o->course_id).' (Kelas '.$o->class_code.')',
                                ])
                                ->all())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Hanya menampilkan Penawaran MK dari Semester aktif.')
                            ->columnSpanFull(),
                        Select::make('classroom_id')
                            ->label('Ruang Kelas')
                            ->relationship('classroom', 'name')
                            ->searchable()
                            ->preload(),
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
                            ->minValue(1)
                            ->default(14),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Resources\ClassSessions\Schemas;

use App\Enums\Scheduling\ClassSessionStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClassSessionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('schedule_id')
                            ->label('Jadwal')
                            ->relationship('schedule', 'id')
                            ->required(),
                        TextInput::make('meeting_number')
                            ->label('Pertemuan Ke')
                            ->required()
                            ->numeric(),
                        DatePicker::make('session_date')
                            ->label('Tanggal Sesi')
                            ->required(),
                        TimePicker::make('start_time')
                            ->label('Jam Mulai')
                            ->required(),
                        TimePicker::make('end_time')
                            ->label('Jam Selesai')
                            ->required(),
                        TextInput::make('topic')
                            ->label('Topik'),
                        Textarea::make('material_summary')
                            ->label('Ringkasan Materi')
                            ->columnSpanFull(),
                        Select::make('status')
                            ->label('Status')
                            ->options(ClassSessionStatus::class)
                            ->default('Scheduled')
                            ->required(),
                        Select::make('substitute_lecturer_id')
                            ->label('Dosen Pengganti')
                            ->relationship('substituteLecturer', 'id'),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

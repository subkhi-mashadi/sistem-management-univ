<?php

namespace App\Filament\Admin\Resources\Semesters\Schemas;

use App\Enums\Academic\SemesterTerm;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SemesterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('academic_calendar_id')
                            ->label('Kalender Akademik')
                            ->relationship('academicCalendar', 'name')
                            ->required(),
                        TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        Select::make('term')
                            ->label('Term')
                            ->options(SemesterTerm::class)
                            ->required(),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required(),
                        DatePicker::make('krs_start')
                            ->label('Mulai KRS'),
                        DatePicker::make('krs_end')
                            ->label('Akhir KRS'),
                        DatePicker::make('lecture_start')
                            ->label('Mulai Kuliah'),
                        DatePicker::make('lecture_end')
                            ->label('Akhir Kuliah'),
                        DatePicker::make('uts_start')
                            ->label('Mulai UTS'),
                        DatePicker::make('uts_end')
                            ->label('Akhir UTS'),
                        DatePicker::make('uas_start')
                            ->label('Mulai UAS'),
                        DatePicker::make('uas_end')
                            ->label('Akhir UAS'),
                        DatePicker::make('grade_input_deadline')
                            ->label('Batas Input Nilai'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ]),
            ]);
    }
}

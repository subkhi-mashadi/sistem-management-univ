<?php

namespace App\Filament\Admin\Resources\Grades\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GradeForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->relationship('student', 'id')
                            ->required(),
                        Select::make('course_offering_id')
                            ->label('Penawaran MK')
                            ->relationship('courseOffering', 'id')
                            ->required(),
                        Select::make('krs_item_id')
                            ->label('Item KRS')
                            ->relationship('krsItem', 'id'),
                        TextInput::make('attendance_score')
                            ->label('Nilai Kehadiran')
                            ->numeric(),
                        TextInput::make('assignment_score')
                            ->label('Nilai Tugas')
                            ->numeric(),
                        TextInput::make('mid_score')
                            ->label('Nilai UTS')
                            ->numeric(),
                        TextInput::make('final_score')
                            ->label('Nilai UAS')
                            ->numeric(),
                        TextInput::make('extra_score')
                            ->label('Nilai Tambahan')
                            ->numeric(),
                        TextInput::make('total_score')
                            ->label('Nilai Total')
                            ->numeric(),
                        TextInput::make('letter_grade')
                            ->label('Huruf Mutu'),
                        TextInput::make('grade_point')
                            ->label('Bobot')
                            ->numeric(),
                        Toggle::make('is_locked')
                            ->label('Terkunci')
                            ->required(),
                        DateTimePicker::make('locked_at')
                            ->label('Dikunci Pada'),
                        TextInput::make('locked_by')
                            ->label('Dikunci Oleh')
                            ->numeric(),
                        TextInput::make('submitted_by')
                            ->label('Diinput Oleh')
                            ->numeric(),
                        DateTimePicker::make('submitted_at')
                            ->label('Tanggal Input'),
                    ]),
            ]);
    }
}

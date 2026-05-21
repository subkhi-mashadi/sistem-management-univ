<?php

namespace App\Filament\Admin\Resources\SksConversions\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SksConversionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('mbkm_enrollment_id')
                            ->label('Pendaftaran MBKM')
                            ->relationship('enrollment', 'id')->searchable()->preload()
                            ->required(),
                        Select::make('course_id')
                            ->label('Mata Kuliah')
                            ->relationship('course', 'name')
                            ->required(),
                        TextInput::make('sks_converted')
                            ->label('SKS Konversi')
                            ->required()
                            ->numeric(),
                        TextInput::make('letter_grade')
                            ->label('Huruf Mutu'),
                        TextInput::make('grade_point')
                            ->label('Bobot')
                            ->numeric(),
                        TextInput::make('approved_by')
                            ->label('Disetujui Oleh')
                            ->numeric(),
                        DateTimePicker::make('approved_at')
                            ->label('Tanggal Disetujui'),
                    ]),
            ]);
    }
}

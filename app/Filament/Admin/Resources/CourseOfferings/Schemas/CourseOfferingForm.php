<?php

namespace App\Filament\Admin\Resources\CourseOfferings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseOfferingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('course_id')
                    ->label('Mata Kuliah')
                    ->relationship('course', 'name')
                    ->required(),
                Select::make('semester_id')
                    ->label('Semester')
                    ->relationship('semester', 'name')
                    ->required(),
                TextInput::make('class_code')
                    ->label('Kelas')
                    ->required(),
                TextInput::make('lecturer_ids')
                    ->label('Dosen'),
                TextInput::make('quota')
                    ->label('Kuota')
                    ->required()
                    ->numeric()
                    ->default(40),
                TextInput::make('enrolled_count')
                    ->label('Terdaftar')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_open')
                    ->label('Dibuka')
                    ->required(),
            ]);
    }
}

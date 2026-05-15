<?php

namespace App\Filament\Admin\Resources\Courses\Schemas;

use App\Enums\Academic\CourseType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('curriculum_id')
                    ->label('Kurikulum')
                    ->relationship('curriculum', 'id')
                    ->required(),
                TextInput::make('code')
                    ->label('Kode')
                    ->required(),
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('name_en')
                    ->label('Nama (EN)'),
                TextInput::make('sks_theory')
                    ->label('SKS Teori')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('sks_practice')
                    ->label('SKS Praktik')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('sks_field')
                    ->label('SKS Lapangan')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_sks')
                    ->label('Total SKS')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('semester')
                    ->label('Semester')
                    ->required()
                    ->numeric(),
                Select::make('course_type')
                    ->label('Jenis MK')
                    ->options(CourseType::class)
                    ->required(),
                Textarea::make('description')
                    ->label('Deskripsi')
                    ->columnSpanFull(),
                TextInput::make('learning_outcomes')
                    ->label('Capaian Pembelajaran'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}

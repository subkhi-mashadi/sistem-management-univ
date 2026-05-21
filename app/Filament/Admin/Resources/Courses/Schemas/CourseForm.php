<?php

namespace App\Filament\Admin\Resources\Courses\Schemas;

use App\Enums\Academic\CourseType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        $recalcTotal = function (Get $get, Set $set): void {
            $total = (int) $get('sks_theory') + (int) $get('sks_practice') + (int) $get('sks_field');
            $set('total_sks', $total);
        };

        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('curriculum_id')
                            ->label('Kurikulum')
                            ->relationship('curriculum', 'code')->searchable()->preload()
                            ->required(),
                        TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        Select::make('semester')
                            ->label('Semester')
                            ->options([
                                1 => 'Semester 1',
                                2 => 'Semester 2',
                                3 => 'Semester 3',
                                4 => 'Semester 4',
                                5 => 'Semester 5',
                                6 => 'Semester 6',
                                7 => 'Semester 7',
                                8 => 'Semester 8',
                            ])
                            ->required(),
                        Select::make('course_type')
                            ->label('Jenis MK')
                            ->options(CourseType::class)
                            ->required(),
                        TextInput::make('sks_theory')
                            ->label('SKS Teori')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated($recalcTotal),
                        TextInput::make('sks_practice')
                            ->label('SKS Praktik')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated($recalcTotal),
                        TextInput::make('sks_field')
                            ->label('SKS Lapangan')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated($recalcTotal),
                        TextInput::make('total_sks')
                            ->label('Total SKS')
                            ->numeric()
                            ->default(0)
                            ->disabled()
                            ->dehydrated()
                            ->helperText('Otomatis dijumlah dari SKS Teori + Praktik + Lapangan.'),
                        TextInput::make('learning_outcomes')
                            ->label('Capaian Pembelajaran'),
                        Textarea::make('description')
                            ->label('Deskripsi')
                            ->columnSpanFull(),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}

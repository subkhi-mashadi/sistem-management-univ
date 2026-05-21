<?php

namespace App\Filament\Admin\Resources\Prerequisites\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PrerequisiteForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('course_id')
                            ->label('Mata Kuliah')
                            ->relationship('course', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('prerequisite_course_id')
                            ->label('MK Prasyarat')
                            ->relationship('prerequisiteCourse', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('minimum_grade')
                            ->label('Nilai Minimum')
                            ->options([
                                'A' => 'A — Sangat Baik (≥ 80)',
                                'AB' => 'AB — Baik Sekali (75–79)',
                                'B' => 'B — Baik (70–74)',
                                'BC' => 'BC — Cukup Baik (65–69)',
                                'C' => 'C — Cukup (60–64)',
                                'D' => 'D — Kurang (50–59)',
                            ])
                            ->default('C')
                            ->helperText('Nilai minimum yang harus dicapai pada MK prasyarat.')
                            ->required(),
                        Select::make('group_no')
                            ->label('Grup Prasyarat')
                            ->options([
                                1 => 'Grup 1 (Utama)',
                                2 => 'Grup 2 (Alternatif)',
                                3 => 'Grup 3',
                                4 => 'Grup 4',
                                5 => 'Grup 5',
                            ])
                            ->default(1)
                            ->helperText('Pakai grup berbeda jika MK punya beberapa alternatif jalur prasyarat.')
                            ->required(),
                    ]),
            ]);
    }
}

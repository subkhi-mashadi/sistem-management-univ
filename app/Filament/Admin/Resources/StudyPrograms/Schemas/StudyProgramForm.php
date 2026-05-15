<?php

namespace App\Filament\Admin\Resources\StudyPrograms\Schemas;

use App\Enums\Academic\Accreditation;
use App\Enums\Academic\DegreeLevel;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudyProgramForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('faculty_id')
                            ->label('Fakultas')
                            ->relationship('faculty', 'name')
                            ->required(),
                        TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        Select::make('degree_level')
                            ->label('Jenjang')
                            ->options(DegreeLevel::class)
                            ->required(),
                        TextInput::make('pddikti_code')
                            ->label('Kode PDDikti'),
                        Select::make('accreditation')
                            ->label('Akreditasi')
                            ->options(Accreditation::class),
                        DatePicker::make('accreditation_valid_until')
                            ->label('Akreditasi Berlaku Hingga'),
                        Select::make('head_id')
                            ->label('Ketua')
                            ->relationship('head', 'name'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ]),
            ]);
    }
}

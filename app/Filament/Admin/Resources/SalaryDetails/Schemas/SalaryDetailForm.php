<?php

namespace App\Filament\Admin\Resources\SalaryDetails\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SalaryDetailForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('salary_id')
                            ->label('Gaji')
                            ->relationship('salary', 'id')->searchable()->preload()
                            ->required(),
                        Select::make('salary_component_id')
                            ->label('Komponen Gaji')
                            ->relationship('component', 'name')->searchable()->preload()
                            ->required(),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('notes')
                            ->label('Catatan'),
                    ]),
            ]);
    }
}

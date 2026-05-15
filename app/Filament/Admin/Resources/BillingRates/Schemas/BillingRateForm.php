<?php

namespace App\Filament\Admin\Resources\BillingRates\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BillingRateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('billing_component_id')
                            ->label('Komponen Tagihan')
                            ->required()
                            ->numeric(),
                        Select::make('study_program_id')
                            ->label('Program Studi')
                            ->relationship('studyProgram', 'name'),
                        TextInput::make('enrollment_year')
                            ->label('Tahun Masuk'),
                        TextInput::make('ukt_group')
                            ->label('Golongan UKT')
                            ->numeric(),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric(),
                        DatePicker::make('effective_from')
                            ->label('Berlaku Dari'),
                        DatePicker::make('effective_to')
                            ->label('Berlaku Hingga'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ]),
            ]);
    }
}

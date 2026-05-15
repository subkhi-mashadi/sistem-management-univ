<?php

namespace App\Filament\Admin\Resources\TaxCalculations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TaxCalculationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('payroll_period_id')
                            ->label('Periode Payroll')
                            ->required()
                            ->numeric(),
                        Select::make('employee_id')
                            ->label('Pegawai')
                            ->relationship('employee', 'id')
                            ->required(),
                        TextInput::make('tax_year')
                            ->label('Tahun Pajak')
                            ->required(),
                        TextInput::make('gross_year_to_date')
                            ->label('Bruto YTD')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('ptkp_category')
                            ->label('Kategori PTKP'),
                        TextInput::make('ptkp_amount')
                            ->label('Jumlah PTKP')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('taxable_income')
                            ->label('Penghasilan Kena Pajak')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('tax_pph21')
                            ->label('PPh 21')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        TextInput::make('tax_pph21_ytd')
                            ->label('PPh 21 YTD')
                            ->required()
                            ->numeric()
                            ->default(0.0),
                        Toggle::make('is_final')
                            ->label('Final')
                            ->required(),
                    ]),
            ]);
    }
}

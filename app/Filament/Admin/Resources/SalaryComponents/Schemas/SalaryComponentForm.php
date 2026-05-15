<?php

namespace App\Filament\Admin\Resources\SalaryComponents\Schemas;

use App\Enums\Hris\CalculationType;
use App\Enums\Hris\SalaryComponentType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SalaryComponentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode')
                    ->required(),
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                Select::make('type')
                    ->label('Tipe')
                    ->options(SalaryComponentType::class)
                    ->required(),
                Select::make('calculation_type')
                    ->label('Tipe Kalkulasi')
                    ->options(CalculationType::class)
                    ->required(),
                Textarea::make('formula')
                    ->label('Formula')
                    ->columnSpanFull(),
                Toggle::make('is_taxable')
                    ->label('Kena Pajak')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}

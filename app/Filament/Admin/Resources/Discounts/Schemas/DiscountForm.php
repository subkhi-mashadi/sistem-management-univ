<?php

namespace App\Filament\Admin\Resources\Discounts\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DiscountForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('invoice_id')
                            ->label('Invoice')
                            ->relationship('invoice', 'id')
                            ->required(),
                        TextInput::make('code')
                            ->label('Kode'),
                        TextInput::make('reason')
                            ->label('Alasan')
                            ->required(),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
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

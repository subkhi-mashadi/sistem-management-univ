<?php

namespace App\Filament\Admin\Resources\InvoiceItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceItemForm
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
                            ->relationship('invoice', 'invoice_number')->searchable()->preload()
                            ->required(),
                        Select::make('billing_component_id')
                            ->label('Komponen Tagihan')
                            ->relationship('component', 'name')->searchable()->preload()
                            ->required(),
                        TextInput::make('description')
                            ->label('Deskripsi')
                            ->required(),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric(),
                        TextInput::make('quantity')
                            ->label('Qty')
                            ->required()
                            ->numeric()
                            ->default(1),
                        TextInput::make('total')
                            ->label('Total')
                            ->required()
                            ->numeric(),
                    ]),
            ]);
    }
}

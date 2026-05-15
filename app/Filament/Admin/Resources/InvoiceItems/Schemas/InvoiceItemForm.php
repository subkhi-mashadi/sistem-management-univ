<?php

namespace App\Filament\Admin\Resources\InvoiceItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class InvoiceItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('invoice_id')
                    ->label('Invoice')
                    ->relationship('invoice', 'id')
                    ->required(),
                TextInput::make('billing_component_id')
                    ->label('Komponen Tagihan')
                    ->required()
                    ->numeric(),
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
            ]);
    }
}

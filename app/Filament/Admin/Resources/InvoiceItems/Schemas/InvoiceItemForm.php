<?php

namespace App\Filament\Admin\Resources\InvoiceItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;

class InvoiceItemForm
{
    public static function configure(Schema $schema): Schema
    {
        $recalcTotal = function (Get $get, Set $set): void {
            $amount = (float) $get('amount');
            $qty = max(1, (int) $get('quantity'));
            $set('total', $amount * $qty);
        };

        return $schema
            ->components([
                Section::make('Item Invoice')
                    ->description('Total otomatis = Nominal × Qty. Setelah disimpan, Subtotal & Total Invoice juga ter-update otomatis via observer.')
                    ->columnSpanFull()
                    ->columns(2)
                    ->components([
                        Select::make('invoice_id')
                            ->label('Invoice')
                            ->relationship('invoice', 'invoice_number')
                            ->searchable()
                            ->preload()
                            ->required(),
                        Select::make('billing_component_id')
                            ->label('Komponen Tagihan')
                            ->relationship('component', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('description')
                            ->label('Deskripsi')
                            ->placeholder('Mis. UKT Semester Ganjil 2026/2027')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('amount')
                            ->label('Nominal Satuan')
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(0)
                            ->required()
                            ->default(0)
                            ->live(onBlur: true)
                            ->afterStateUpdated($recalcTotal),
                        TextInput::make('quantity')
                            ->label('Qty')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->default(1)
                            ->live(onBlur: true)
                            ->afterStateUpdated($recalcTotal),
                        TextInput::make('total')
                            ->label('Total Item')
                            ->prefix('Rp')
                            ->disabled()
                            ->dehydrated()
                            ->default(0)
                            ->columnSpanFull()
                            ->formatStateUsing(fn ($state) => number_format((float) ($state ?? 0), 0, ',', '.'))
                            ->extraInputAttributes(['class' => 'font-bold text-primary-600'])
                            ->helperText('Otomatis = Nominal × Qty.'),
                    ]),
            ]);
    }
}

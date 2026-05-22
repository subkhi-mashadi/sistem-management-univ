<?php

namespace App\Filament\Admin\Resources\Discounts\Schemas;

use App\Models\Invoice;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DiscountForm
{
    private static function invoiceLabel(Invoice $i): string
    {
        $mhs = $i->student?->user?->full_name ?? $i->student?->user?->name ?? 'Mhs';

        return $i->invoice_number.' — '.$mhs.' (Total Rp '.number_format((float) $i->total_amount, 0, ',', '.').')';
    }

    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Diskon')
                    ->columnSpanFull()
                    ->schema([
                        Select::make('invoice_id')
                            ->label('Invoice')
                            ->options(fn () => Invoice::query()
                                ->with('student.user:id,full_name,name')
                                ->orderByDesc('created_at')
                                ->limit(100)
                                ->get()
                                ->mapWithKeys(fn (Invoice $i) => [$i->id => self::invoiceLabel($i)])
                                ->all())
                            ->getOptionLabelUsing(function ($value): ?string {
                                $inv = Invoice::with('student.user:id,full_name,name')->find($value);

                                return $inv ? self::invoiceLabel($inv) : null;
                            })
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpanFull(),

                        TextInput::make('code')
                            ->label('Kode Diskon')
                            ->placeholder('Auto: DSK-YYYYMM-NNNN')
                            ->disabled()
                            ->dehydrated(false)
                            ->columnSpan(1),

                        TextInput::make('amount')
                            ->label('Nominal Diskon')
                            ->prefix('Rp')
                            ->numeric()
                            ->minValue(1)
                            ->required()
                            ->columnSpan(1),

                        Textarea::make('reason')
                            ->label('Alasan')
                            ->placeholder('Mis. Penerima Beasiswa KIP-K, Diskon early bird, Kebijakan Rektor')
                            ->rows(2)
                            ->required()
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}

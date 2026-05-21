<?php

namespace App\Filament\Admin\Resources\Fines\Schemas;

use App\Enums\Finance\FineType;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class FineForm
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
                        Select::make('type')
                            ->label('Tipe')
                            ->options(FineType::class)
                            ->default('Late')
                            ->required(),
                        TextInput::make('amount')
                            ->label('Jumlah')
                            ->required()
                            ->numeric(),
                        TextInput::make('description')
                            ->label('Deskripsi'),
                        Toggle::make('waived')
                            ->label('Dibebaskan')
                            ->required(),
                        TextInput::make('waived_by')
                            ->label('Dibebaskan Oleh')
                            ->numeric(),
                        DateTimePicker::make('waived_at')
                            ->label('Tanggal Pembebasan'),
                        TextInput::make('waive_reason')
                            ->label('Alasan Pembebasan'),
                    ]),
            ]);
    }
}

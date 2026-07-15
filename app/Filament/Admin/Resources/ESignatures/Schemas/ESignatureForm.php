<?php

namespace App\Filament\Admin\Resources\ESignatures\Schemas;

use App\Filament\Forms\Components\SignaturePad;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ESignatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Tanda Tangan')
                    ->columnSpanFull()
                    ->columns(1)->components([
                        Select::make('user_id')
                            ->label('Pengguna')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        SignaturePad::make('signature_data')
                            ->label('Tanda Tangan'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->default(true)
                            ->required(),
                    ]),
            ]);
    }
}

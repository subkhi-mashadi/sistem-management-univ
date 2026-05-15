<?php

namespace App\Filament\Admin\Resources\ESignatures\Schemas;

use App\Enums\EOffice\SignatureProvider;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ESignatureForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Pengguna')
                    ->relationship('user', 'name')
                    ->required(),
                Select::make('provider')
                    ->label('Provider')
                    ->options(SignatureProvider::class)
                    ->default('BSrE')
                    ->required(),
                Textarea::make('certificate_data')
                    ->label('Data Sertifikat')
                    ->columnSpanFull(),
                TextInput::make('certificate_serial')
                    ->label('Serial Sertifikat'),
                DateTimePicker::make('valid_from')
                    ->label('Berlaku Dari'),
                DateTimePicker::make('valid_until')
                    ->label('Berlaku Hingga'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}

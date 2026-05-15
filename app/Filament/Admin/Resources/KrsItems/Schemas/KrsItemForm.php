<?php

namespace App\Filament\Admin\Resources\KrsItems\Schemas;

use App\Enums\Krs\KrsItemStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class KrsItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('enrollment_id')
                    ->label('KRS')
                    ->relationship('enrollment', 'id')
                    ->required(),
                Select::make('course_offering_id')
                    ->label('Penawaran MK')
                    ->relationship('courseOffering', 'id')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options(KrsItemStatus::class)
                    ->default('Active')
                    ->required(),
                DateTimePicker::make('dropped_at')
                    ->label('Tanggal Drop'),
            ]);
    }
}

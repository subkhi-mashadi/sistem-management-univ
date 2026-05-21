<?php

namespace App\Filament\Admin\Resources\KrsItems\Schemas;

use App\Enums\Krs\KrsItemStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class KrsItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('enrollment_id')
                            ->label('KRS')
                            ->relationship('enrollment', 'id')->searchable()->preload()
                            ->required(),
                        Select::make('course_offering_id')
                            ->label('Penawaran MK')
                            ->relationship('courseOffering', 'class_code')->searchable()->preload()
                            ->required(),
                        Select::make('status')
                            ->label('Status')
                            ->options(KrsItemStatus::class)
                            ->default('Active')
                            ->required(),
                        DateTimePicker::make('dropped_at')
                            ->label('Tanggal Drop'),
                    ]),
            ]);
    }
}

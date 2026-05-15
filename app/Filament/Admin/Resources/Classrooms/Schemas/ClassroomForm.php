<?php

namespace App\Filament\Admin\Resources\Classrooms\Schemas;

use App\Enums\Academic\RoomType;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClassroomForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('code')
                    ->label('Kode')
                    ->required(),
                TextInput::make('name')
                    ->label('Nama')
                    ->required(),
                TextInput::make('building')
                    ->label('Gedung'),
                TextInput::make('floor')
                    ->label('Lantai'),
                TextInput::make('capacity')
                    ->label('Kapasitas')
                    ->required()
                    ->numeric(),
                Select::make('room_type')
                    ->label('Tipe Ruangan')
                    ->options(RoomType::class)
                    ->default('Kelas')
                    ->required(),
                TextInput::make('facilities')
                    ->label('Fasilitas'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}

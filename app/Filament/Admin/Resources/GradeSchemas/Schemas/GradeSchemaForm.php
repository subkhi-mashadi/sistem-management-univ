<?php

namespace App\Filament\Admin\Resources\GradeSchemas\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class GradeSchemaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('faculty_id')
                    ->label('Fakultas')
                    ->relationship('faculty', 'name'),
                TextInput::make('letter')
                    ->label('Huruf')
                    ->required(),
                TextInput::make('min_score')
                    ->label('Nilai Minimum')
                    ->required()
                    ->numeric(),
                TextInput::make('max_score')
                    ->label('Nilai Maksimum')
                    ->required()
                    ->numeric(),
                TextInput::make('grade_point')
                    ->label('Bobot')
                    ->required()
                    ->numeric(),
                TextInput::make('description')
                    ->label('Deskripsi'),
                Toggle::make('is_active')
                    ->label('Aktif')
                    ->required(),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Resources\ThesisAdvisors\Schemas;

use App\Enums\Thesis\ThesisAdvisorStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ThesisAdvisorForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('thesis_topic_id')
                    ->label('Topik Skripsi')
                    ->relationship('thesisTopic', 'title')
                    ->required(),
                Select::make('lecturer_id')
                    ->label('Dosen')
                    ->relationship('lecturer', 'id')
                    ->required(),
                TextInput::make('advisor_order')
                    ->label('Urutan Pembimbing')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('assigned_at')
                    ->label('Ditugaskan')
                    ->required(),
                Select::make('status')
                    ->label('Status')
                    ->options(ThesisAdvisorStatus::class)
                    ->default('Active')
                    ->required(),
            ]);
    }
}

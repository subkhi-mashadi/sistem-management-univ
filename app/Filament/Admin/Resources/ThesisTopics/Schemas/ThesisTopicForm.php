<?php

namespace App\Filament\Admin\Resources\ThesisTopics\Schemas;

use App\Enums\Thesis\ThesisTopicStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ThesisTopicForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->label('Mahasiswa')
                    ->relationship('student', 'id')
                    ->required(),
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                TextInput::make('title_en')
                    ->label('Judul (EN)'),
                Textarea::make('abstract')
                    ->label('Abstrak')
                    ->columnSpanFull(),
                TextInput::make('keywords')
                    ->label('Kata Kunci'),
                TextInput::make('research_field')
                    ->label('Bidang Penelitian'),
                TextInput::make('similarity_score')
                    ->label('Skor Kemiripan')
                    ->numeric(),
                Select::make('status')
                    ->label('Status')
                    ->options(ThesisTopicStatus::class)
                    ->default('Draft')
                    ->required(),
                TextInput::make('approved_by')
                    ->label('Disetujui Oleh')
                    ->numeric(),
                DateTimePicker::make('approved_at')
                    ->label('Tanggal Disetujui'),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Resources\ThesisDefenses\Schemas;

use App\Enums\Thesis\DefenseStatus;
use App\Enums\Thesis\DefenseType;
use App\Models\Lecturer;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ThesisDefenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('thesis_topic_id')
                            ->label('Topik Skripsi')
                            ->relationship('thesisTopic', 'title')
                            ->required(),
                        Select::make('defense_type')
                            ->label('Jenis Sidang')
                            ->options(DefenseType::class)
                            ->required(),
                        DateTimePicker::make('scheduled_at')
                            ->label('Dijadwalkan')
                            ->required(),
                        TextInput::make('room')
                            ->label('Ruangan'),
                        Select::make('examiner_ids')
                            ->label('Penguji')
                            ->options(fn () => Lecturer::with('user')->get()->pluck('user.name', 'id'))
                            ->multiple()
                            ->searchable()
                            ->preload()
                            ->required(),
                        TextInput::make('plagiarism_score')
                            ->label('Skor Plagiarisme')
                            ->numeric(),
                        TextInput::make('final_score')
                            ->label('Nilai UAS')
                            ->numeric(),
                        TextInput::make('letter_grade')
                            ->label('Huruf Mutu'),
                        Select::make('status')
                            ->label('Status')
                            ->options(DefenseStatus::class)
                            ->default('Scheduled')
                            ->required(),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

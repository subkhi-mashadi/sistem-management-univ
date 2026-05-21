<?php

namespace App\Filament\Admin\Resources\GradeAppeals\Schemas;

use App\Enums\Krs\AppealStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class GradeAppealForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('grade_id')
                            ->label('Nilai')
                            ->relationship('grade', 'id')->searchable()->preload()
                            ->required(),
                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->relationship('student', 'nim')->searchable()->preload()
                            ->required(),
                        Textarea::make('reason')
                            ->label('Alasan')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('original_letter')
                            ->label('Huruf Asal')
                            ->required(),
                        TextInput::make('revised_letter')
                            ->label('Huruf Revisi'),
                        TextInput::make('original_score')
                            ->label('Nilai Asal')
                            ->numeric(),
                        TextInput::make('revised_score')
                            ->label('Nilai Revisi')
                            ->numeric(),
                        Select::make('status')
                            ->label('Status')
                            ->options(AppealStatus::class)
                            ->default('Pending')
                            ->required(),
                        TextInput::make('reviewed_by')
                            ->label('Direview Oleh')
                            ->numeric(),
                        DateTimePicker::make('reviewed_at')
                            ->label('Tanggal Review'),
                        Textarea::make('review_notes')
                            ->label('Catatan Review')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

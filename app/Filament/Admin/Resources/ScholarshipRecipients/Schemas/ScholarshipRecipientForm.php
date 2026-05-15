<?php

namespace App\Filament\Admin\Resources\ScholarshipRecipients\Schemas;

use App\Enums\Finance\ScholarshipRecipientStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class ScholarshipRecipientForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('scholarship_id')
                    ->label('Beasiswa')
                    ->relationship('scholarship', 'name')
                    ->required(),
                Select::make('student_id')
                    ->label('Mahasiswa')
                    ->relationship('student', 'id')
                    ->required(),
                Select::make('semester_id')
                    ->label('Semester')
                    ->relationship('semester', 'name'),
                DatePicker::make('start_date')
                    ->label('Tanggal Mulai')
                    ->required(),
                DatePicker::make('end_date')
                    ->label('Tanggal Selesai'),
                Select::make('status')
                    ->label('Status')
                    ->options(ScholarshipRecipientStatus::class)
                    ->default('Active')
                    ->required(),
                TextInput::make('granted_amount')
                    ->label('Jumlah Diberikan')
                    ->numeric(),
                Textarea::make('notes')
                    ->label('Catatan')
                    ->columnSpanFull(),
            ]);
    }
}

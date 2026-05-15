<?php

namespace App\Filament\Admin\Resources\LetterRequests\Schemas;

use App\Enums\EOffice\LetterRequestStatus;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LetterRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('letter_number')
                            ->label('Nomor Surat'),
                        TextInput::make('letter_template_id')
                            ->label('Template Surat')
                            ->required()
                            ->numeric(),
                        Select::make('workflow_id')
                            ->label('Workflow')
                            ->relationship('workflow', 'name'),
                        Select::make('requester_id')
                            ->label('Pengaju')
                            ->relationship('requester', 'name')
                            ->required(),
                        Select::make('student_id')
                            ->label('Mahasiswa')
                            ->relationship('student', 'id'),
                        TextInput::make('form_data')
                            ->label('Data Form')
                            ->required(),
                        Select::make('current_step_id')
                            ->label('Step Saat Ini')
                            ->relationship('currentStep', 'name'),
                        Select::make('status')
                            ->label('Status')
                            ->options(LetterRequestStatus::class)
                            ->default('Draft')
                            ->required(),
                        TextInput::make('pdf_url')
                            ->label('PDF')
                            ->url(),
                        TextInput::make('qr_code')
                            ->label('QR Code'),
                        DatePicker::make('qr_valid_until')
                            ->label('QR Berlaku Hingga'),
                        DateTimePicker::make('issued_at')
                            ->label('Diterbitkan'),
                        Textarea::make('notes')
                            ->label('Catatan')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Resources\Approvals\Schemas;

use App\Enums\EOffice\ApprovalAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ApprovalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('letter_request_id')
                            ->label('Pengajuan Surat')
                            ->relationship('letterRequest', 'id')
                            ->required(),
                        TextInput::make('workflow_step_id')
                            ->label('Step Workflow')
                            ->required()
                            ->numeric(),
                        Select::make('approver_id')
                            ->label('Approver')
                            ->relationship('approver', 'name')
                            ->required(),
                        Select::make('action')
                            ->label('Aksi')
                            ->options(ApprovalAction::class)
                            ->default('Pending')
                            ->required(),
                        Textarea::make('comments')
                            ->label('Komentar')
                            ->columnSpanFull(),
                        DateTimePicker::make('acted_at')
                            ->label('Waktu Aksi'),
                        TextInput::make('delegated_to')
                            ->label('Didelegasi Ke')
                            ->numeric(),
                    ]),
            ]);
    }
}

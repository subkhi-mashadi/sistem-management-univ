<?php

namespace App\Filament\Admin\Resources\LeaveRequests\Schemas;

use App\Enums\Hris\LeaveStatus;
use App\Enums\Hris\LeaveType;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LeaveRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        Select::make('employee_id')
                            ->label('Pegawai')
                            ->relationship('employee', 'id')
                            ->required(),
                        Select::make('leave_type')
                            ->label('Jenis Cuti')
                            ->options(LeaveType::class)
                            ->required(),
                        DatePicker::make('start_date')
                            ->label('Tanggal Mulai')
                            ->required(),
                        DatePicker::make('end_date')
                            ->label('Tanggal Selesai')
                            ->required(),
                        TextInput::make('total_days')
                            ->label('Total Hari')
                            ->required()
                            ->numeric(),
                        Textarea::make('reason')
                            ->label('Alasan')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('attachment_url')
                            ->label('Lampiran')
                            ->url(),
                        Select::make('status')
                            ->label('Status')
                            ->options(LeaveStatus::class)
                            ->default('Pending')
                            ->required(),
                        TextInput::make('approved_by')
                            ->label('Disetujui Oleh')
                            ->numeric(),
                        DateTimePicker::make('approved_at')
                            ->label('Tanggal Disetujui'),
                        Textarea::make('rejection_reason')
                            ->label('Alasan Ditolak')
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}

<?php

namespace App\Filament\Student\Resources\ScholarshipRecipients\Tables;

use App\Enums\Finance\ScholarshipRecipientStatus;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ScholarshipRecipientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('scholarship.name')->label('Beasiswa')->searchable(),
                TextColumn::make('semester.name')->label('Semester'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof ScholarshipRecipientStatus ? $state->value : $state) {
                        'Pending' => 'warning',
                        'Active' => 'success',
                        'Rejected', 'Revoked' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('granted_amount')
                    ->label('Jumlah Disetujui')
                    ->money('IDR')
                    ->placeholder('—'),
                TextColumn::make('created_at')->label('Diajukan')->dateTime()->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}

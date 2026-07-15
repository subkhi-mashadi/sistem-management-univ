<?php

namespace App\Filament\Lecturer\Resources\Enrollments\Tables;

use App\Enums\Krs\EnrollmentStatus;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class EnrollmentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('student.nim')
                    ->label('NIM')
                    ->searchable(),

                TextColumn::make('student.user.full_name')
                    ->label('Mahasiswa')
                    ->searchable(),

                TextColumn::make('semester.name')
                    ->label('Semester')
                    ->badge(),

                TextColumn::make('total_sks_taken')
                    ->label('SKS')
                    ->alignCenter(),

                TextColumn::make('items_count')
                    ->label('Jumlah MK')
                    ->counts('items')
                    ->alignCenter(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof EnrollmentStatus ? $state->value : $state) {
                        'Approved' => 'success',
                        'Submitted' => 'warning',
                        'Rejected' => 'danger',
                        'Locked'   => 'gray',
                        default    => 'info',
                    }),

                TextColumn::make('approved_at')
                    ->label('Disetujui')
                    ->dateTime()
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options(EnrollmentStatus::class)
                    ->default(EnrollmentStatus::Submitted->value),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}

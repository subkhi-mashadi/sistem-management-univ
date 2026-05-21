<?php

namespace App\Filament\Admin\Resources\LetterRequests\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class LetterRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('letter_number')
                    ->label('Nomor Surat')
                    ->searchable(),
                TextColumn::make('template.name')
                    ->label('Template Surat')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('workflow.name')
                    ->label('Workflow')
                    ->searchable(),
                TextColumn::make('requester.name')
                    ->label('Pengaju')
                    ->searchable(),
                TextColumn::make('student.user.name')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('currentStep.name')
                    ->label('Step Saat Ini')
                    ->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('pdf_url')
                    ->label('PDF')
                    ->searchable(),
                TextColumn::make('qr_code')
                    ->label('QR Code')
                    ->searchable(),
                TextColumn::make('qr_valid_until')
                    ->label('QR Berlaku Hingga')
                    ->date()
                    ->sortable(),
                TextColumn::make('issued_at')
                    ->label('Diterbitkan')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('deleted_at')
                    ->label('Dihapus')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}

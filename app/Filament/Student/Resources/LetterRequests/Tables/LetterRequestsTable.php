<?php

namespace App\Filament\Student\Resources\LetterRequests\Tables;

use App\Enums\EOffice\LetterRequestStatus;
use App\Models\LetterRequest;
use App\Services\EOffice\LetterWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use Throwable;

class LetterRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('letter_number')->label('Nomor Surat')->placeholder('Belum ada'),
                TextColumn::make('template.name')->label('Jenis Surat')->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof LetterRequestStatus ? $state->value : $state) {
                        'Draft' => 'gray',
                        'In Progress' => 'warning',
                        'Approved' => 'info',
                        'Issued' => 'success',
                        'Rejected', 'Cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('currentStep.name')->label('Step Saat Ini')->placeholder('—'),
                TextColumn::make('created_at')->label('Diajukan')->dateTime()->sortable(),
            ])
            ->recordActions([
                Action::make('submit')
                    ->label('Submit')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->visible(fn (LetterRequest $record) => $record->status === LetterRequestStatus::Draft)
                    ->requiresConfirmation()
                    ->action(function (LetterRequest $record) {
                        try {
                            app(LetterWorkflowService::class)->submit($record);
                            Notification::make()->title('Pengajuan disubmit')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal submit')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Action::make('download')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn (LetterRequest $record) => $record->status === LetterRequestStatus::Issued && $record->pdf_url)
                    ->url(fn (LetterRequest $record) => Storage::disk('public')->url($record->pdf_url))
                    ->openUrlInNewTab(),
                ViewAction::make(),
                EditAction::make()
                    ->visible(fn (LetterRequest $record) => $record->status === LetterRequestStatus::Draft),
                DeleteAction::make()
                    ->visible(fn (LetterRequest $record) => $record->status === LetterRequestStatus::Draft),
            ]);
    }
}

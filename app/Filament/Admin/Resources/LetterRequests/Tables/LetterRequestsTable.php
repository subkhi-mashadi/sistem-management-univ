<?php

namespace App\Filament\Admin\Resources\LetterRequests\Tables;

use App\Enums\EOffice\LetterRequestStatus;
use App\Models\LetterRequest;
use App\Services\EOffice\LetterIssuanceService;
use App\Services\EOffice\LetterWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Storage;
use RuntimeException;
use Throwable;

class LetterRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('letter_number')
                    ->label('Nomor Surat')
                    ->searchable()
                    ->placeholder('—'),
                TextColumn::make('template.name')
                    ->label('Template Surat')
                    ->searchable(),
                TextColumn::make('requester.name')
                    ->label('Pengaju')
                    ->searchable(),
                TextColumn::make('student.user.name')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('currentStep.name')
                    ->label('Step Saat Ini')
                    ->placeholder('—'),
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
                TextColumn::make('issued_at')
                    ->label('Diterbitkan')
                    ->dateTime()
                    ->sortable()
                    ->placeholder('—'),
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
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (LetterRequest $record) => $record->status === LetterRequestStatus::InProgress
                        && app(LetterWorkflowService::class)->userCanAct($record, auth()->user()))
                    ->schema([
                        Textarea::make('comments')->label('Komentar'),
                    ])
                    ->action(function (LetterRequest $record, array $data) {
                        try {
                            $service = app(LetterWorkflowService::class);
                            $approval = $service->pendingApprovalFor($record, auth()->user());
                            if (! $approval) {
                                throw new RuntimeException('Tidak ada approval yang bisa diproses untuk Anda.');
                            }
                            $service->approve($approval, auth()->user(), $data['comments'] ?? null);
                            Notification::make()->title('Disetujui')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal approve')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (LetterRequest $record) => $record->status === LetterRequestStatus::InProgress
                        && $record->currentStep?->can_reject
                        && app(LetterWorkflowService::class)->userCanAct($record, auth()->user()))
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('comments')->label('Alasan Penolakan')->required(),
                    ])
                    ->action(function (LetterRequest $record, array $data) {
                        try {
                            $service = app(LetterWorkflowService::class);
                            $approval = $service->pendingApprovalFor($record, auth()->user());
                            if (! $approval) {
                                throw new RuntimeException('Tidak ada approval yang bisa diproses untuk Anda.');
                            }
                            $service->reject($approval, auth()->user(), $data['comments'] ?? null);
                            Notification::make()->title('Pengajuan ditolak')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal reject')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Action::make('issue')
                    ->label('Terbitkan')
                    ->icon('heroicon-o-document-check')
                    ->color('success')
                    ->visible(fn (LetterRequest $record) => $record->status === LetterRequestStatus::Approved)
                    ->requiresConfirmation()
                    ->action(function (LetterRequest $record) {
                        try {
                            app(LetterIssuanceService::class)->issue($record);
                            Notification::make()->title('Surat diterbitkan')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal menerbitkan')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Action::make('download')
                    ->label('Unduh PDF')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->color('gray')
                    ->visible(fn (LetterRequest $record) => $record->status === LetterRequestStatus::Issued && $record->pdf_url)
                    ->url(fn (LetterRequest $record) => Storage::disk('public')->url($record->pdf_url))
                    ->openUrlInNewTab(),
                Action::make('reprint')
                    ->label('Cetak Ulang')
                    ->icon('heroicon-o-arrow-path')
                    ->color('gray')
                    ->visible(fn (LetterRequest $record) => $record->status === LetterRequestStatus::Issued)
                    ->requiresConfirmation()
                    ->modalDescription('PDF akan digenerate ulang pakai template & data terkini. Nomor surat, QR code, dan tanggal terbit tidak berubah.')
                    ->action(function (LetterRequest $record) {
                        try {
                            app(LetterIssuanceService::class)->reprint($record);
                            Notification::make()->title('PDF berhasil dicetak ulang')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal cetak ulang')->body($e->getMessage())->danger()->send();
                        }
                    }),
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

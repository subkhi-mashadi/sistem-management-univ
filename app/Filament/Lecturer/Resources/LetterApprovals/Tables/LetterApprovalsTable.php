<?php

namespace App\Filament\Lecturer\Resources\LetterApprovals\Tables;

use App\Models\LetterRequest;
use App\Services\EOffice\LetterWorkflowService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use RuntimeException;
use Throwable;

class LetterApprovalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('letter_number')->label('Nomor Surat')->placeholder('—'),
                TextColumn::make('template.name')->label('Jenis Surat')->searchable(),
                TextColumn::make('student.user.name')->label('Mahasiswa')->searchable(),
                TextColumn::make('currentStep.name')->label('Step Saat Ini'),
                TextColumn::make('status')->label('Status')->badge()->color('warning'),
                TextColumn::make('created_at')->label('Diajukan')->dateTime()->sortable(),
            ])
            ->recordActions([
                ViewAction::make(),
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
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
                    ->visible(fn (LetterRequest $record) => $record->currentStep?->can_reject)
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
            ]);
    }
}

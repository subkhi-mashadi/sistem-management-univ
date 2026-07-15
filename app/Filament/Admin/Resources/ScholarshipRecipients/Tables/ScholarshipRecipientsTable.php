<?php

namespace App\Filament\Admin\Resources\ScholarshipRecipients\Tables;

use App\Enums\Finance\ScholarshipRecipientStatus;
use App\Models\ScholarshipRecipient;
use App\Services\Finance\ScholarshipApplicationService;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Throwable;

class ScholarshipRecipientsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('scholarship.name')
                    ->label('Beasiswa')
                    ->searchable(),
                TextColumn::make('student.user.name')
                    ->label('Mahasiswa')
                    ->searchable(),
                TextColumn::make('semester.name')
                    ->label('Semester')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->label('Tanggal Mulai')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->label('Tanggal Selesai')
                    ->date()
                    ->sortable(),
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
                    ->label('Jumlah Diberikan')
                    ->money('IDR')
                    ->placeholder('—')
                    ->sortable(),
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
                //
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (ScholarshipRecipient $record) => $record->status === ScholarshipRecipientStatus::Pending)
                    ->requiresConfirmation()
                    ->modalDescription('Jumlah beasiswa akan dihitung otomatis dari coverage_type/coverage_value beasiswa ini terhadap invoice mahasiswa di semester tsb.')
                    ->action(function (ScholarshipRecipient $record) {
                        try {
                            app(ScholarshipApplicationService::class)->approve($record);
                            Notification::make()->title('Beasiswa disetujui')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal approve')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ScholarshipRecipient $record) => $record->status === ScholarshipRecipientStatus::Pending)
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('reason')->label('Alasan Penolakan')->required(),
                    ])
                    ->action(function (ScholarshipRecipient $record, array $data) {
                        try {
                            app(ScholarshipApplicationService::class)->reject($record, $data['reason'] ?? null);
                            Notification::make()->title('Pengajuan ditolak')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal reject')->body($e->getMessage())->danger()->send();
                        }
                    }),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

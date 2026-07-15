<?php

namespace App\Filament\Lecturer\Resources\ThesisAdvisees\Tables;

use App\Enums\Thesis\ThesisTopicStatus;
use App\Models\ThesisTopic;
use App\Services\Thesis\ThesisTopicService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Throwable;

class ThesisAdviseesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')->label('Mahasiswa')->searchable(),
                TextColumn::make('student.nim')->label('NIM')->searchable(),
                TextColumn::make('title')->label('Judul')->wrap()->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof ThesisTopicStatus ? $state->value : $state) {
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Revision', 'Submitted', 'Under Review' => 'warning',
                        default => 'gray',
                    }),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (ThesisTopic $record) => $record->status === ThesisTopicStatus::Submitted)
                    ->requiresConfirmation()
                    ->action(function (ThesisTopic $record) {
                        try {
                            app(ThesisTopicService::class)->approve($record);
                            Notification::make()->title('Topik disetujui')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal approve')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn (ThesisTopic $record) => $record->status === ThesisTopicStatus::Submitted)
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('notes')->label('Alasan Penolakan')->required(),
                    ])
                    ->action(function (ThesisTopic $record, array $data) {
                        try {
                            app(ThesisTopicService::class)->reject($record, $data['notes']);
                            Notification::make()->title('Topik ditolak')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal reject')->body($e->getMessage())->danger()->send();
                        }
                    }),
                Action::make('requestRevision')
                    ->label('Minta Revisi')
                    ->icon('heroicon-o-pencil-square')
                    ->color('warning')
                    ->visible(fn (ThesisTopic $record) => $record->status === ThesisTopicStatus::Submitted)
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('notes')->label('Catatan Revisi')->required(),
                    ])
                    ->action(function (ThesisTopic $record, array $data) {
                        try {
                            app(ThesisTopicService::class)->requestRevision($record, $data['notes']);
                            Notification::make()->title('Revisi diminta')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal')->body($e->getMessage())->danger()->send();
                        }
                    }),
                ViewAction::make(),
            ]);
    }
}

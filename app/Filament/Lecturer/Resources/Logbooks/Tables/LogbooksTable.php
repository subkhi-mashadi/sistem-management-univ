<?php

namespace App\Filament\Lecturer\Resources\Logbooks\Tables;

use App\Models\Logbook;
use App\Services\Thesis\LogbookService;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Throwable;

class LogbooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('session_date', 'desc')
            ->columns([
                TextColumn::make('student.user.name')->label('Mahasiswa')->searchable(),
                TextColumn::make('thesisTopic.title')->label('Topik')->limit(30),
                TextColumn::make('session_date')->label('Tanggal')->date()->sortable(),
                TextColumn::make('topic_discussed')->label('Topik Dibahas')->limit(40),
                IconColumn::make('is_verified')->label('Diverifikasi')->boolean(),
            ])
            ->recordActions([
                Action::make('verify')
                    ->label('Verifikasi')
                    ->icon('heroicon-o-check-badge')
                    ->color('success')
                    ->visible(fn (Logbook $record) => ! $record->is_verified)
                    ->requiresConfirmation()
                    ->schema([
                        Textarea::make('feedback')->label('Feedback (opsional)'),
                    ])
                    ->action(function (Logbook $record, array $data) {
                        try {
                            app(LogbookService::class)->verify($record, $data['feedback'] ?? null);
                            Notification::make()->title('Logbook diverifikasi')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal verifikasi')->body($e->getMessage())->danger()->send();
                        }
                    }),
                ViewAction::make(),
            ]);
    }
}

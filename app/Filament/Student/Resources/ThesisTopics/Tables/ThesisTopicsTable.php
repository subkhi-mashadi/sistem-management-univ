<?php

namespace App\Filament\Student\Resources\ThesisTopics\Tables;

use App\Enums\Thesis\ThesisTopicStatus;
use App\Models\ThesisTopic;
use App\Services\Thesis\ThesisTopicService;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Throwable;

class ThesisTopicsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('title')->label('Judul')->wrap()->searchable(),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn ($state) => match ($state instanceof ThesisTopicStatus ? $state->value : $state) {
                        'Draft' => 'gray',
                        'Submitted', 'Under Review' => 'warning',
                        'Approved' => 'success',
                        'Rejected' => 'danger',
                        'Revision' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('review_notes')->label('Catatan Review')->limit(40)->placeholder('—'),
                TextColumn::make('created_at')->label('Diajukan')->dateTime()->sortable(),
            ])
            ->recordActions([
                Action::make('submit')
                    ->label('Submit')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('info')
                    ->visible(fn (ThesisTopic $record) => in_array($record->status, [
                        ThesisTopicStatus::Draft,
                        ThesisTopicStatus::Rejected,
                        ThesisTopicStatus::Revision,
                    ], true))
                    ->requiresConfirmation()
                    ->action(function (ThesisTopic $record) {
                        try {
                            app(ThesisTopicService::class)->submit($record);
                            Notification::make()->title('Topik disubmit')->success()->send();
                        } catch (Throwable $e) {
                            Notification::make()->title('Gagal submit')->body($e->getMessage())->danger()->send();
                        }
                    }),
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make()
                    ->visible(fn (ThesisTopic $record) => $record->status === ThesisTopicStatus::Draft),
            ]);
    }
}

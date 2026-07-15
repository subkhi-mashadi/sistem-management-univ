<?php

namespace App\Filament\Student\Resources\Logbooks\Tables;

use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LogbooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('session_date', 'desc')
            ->columns([
                TextColumn::make('thesisTopic.title')->label('Topik')->limit(30),
                TextColumn::make('session_date')->label('Tanggal')->date()->sortable(),
                TextColumn::make('topic_discussed')->label('Topik Dibahas')->limit(40),
                IconColumn::make('is_verified')->label('Diverifikasi')->boolean(),
                TextColumn::make('lecturer_feedback')->label('Feedback Dosen')->limit(40)->placeholder('—'),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}

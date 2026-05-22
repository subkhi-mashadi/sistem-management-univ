<?php

namespace App\Filament\Admin\Resources\Invoices\RelationManagers;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ItemsRelationManager extends RelationManager
{
    protected static string $relationship = 'items';

    protected static ?string $title = 'Rincian Item Invoice';

    public function form(Schema $schema): Schema
    {
        $recalcTotal = function (Get $get, Set $set): void {
            $amount = (float) $get('amount');
            $qty = max(1, (int) $get('quantity'));
            $set('total', $amount * $qty);
        };

        return $schema
            ->columns(2)
            ->components([
                Select::make('billing_component_id')
                    ->label('Komponen Tagihan')
                    ->relationship('component', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('description')
                    ->label('Deskripsi')
                    ->required(),
                TextInput::make('amount')
                    ->label('Nominal Satuan')
                    ->prefix('Rp')
                    ->numeric()
                    ->minValue(0)
                    ->required()
                    ->default(0)
                    ->live(onBlur: true)
                    ->afterStateUpdated($recalcTotal),
                TextInput::make('quantity')
                    ->label('Qty')
                    ->numeric()
                    ->minValue(1)
                    ->required()
                    ->default(1)
                    ->live(onBlur: true)
                    ->afterStateUpdated($recalcTotal),
                TextInput::make('total')
                    ->label('Total Item')
                    ->prefix('Rp')
                    ->disabled()
                    ->dehydrated()
                    ->default(0)
                    ->columnSpanFull()
                    ->formatStateUsing(fn ($state) => number_format((float) ($state ?? 0), 0, ',', '.'))
                    ->extraInputAttributes(['class' => 'font-bold text-primary-600']),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('description')
            ->columns([
                TextColumn::make('component.name')
                    ->label('Komponen')
                    ->badge(),
                TextColumn::make('description')
                    ->label('Deskripsi')
                    ->wrap(),
                TextColumn::make('amount')
                    ->label('Nominal')
                    ->money('IDR'),
                TextColumn::make('quantity')
                    ->label('Qty')
                    ->alignCenter(),
                TextColumn::make('total')
                    ->label('Total')
                    ->money('IDR')
                    ->weight('bold'),
            ])
            ->headerActions([
                CreateAction::make()
                    ->label('Tambah Item')
                    ->icon('heroicon-o-plus'),
            ])
            ->recordActions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}

<?php

namespace App\Filament\Admin\Resources\LetterTemplates\Schemas;

use App\Enums\EOffice\LetterCategory;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class LetterTemplateForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informasi Utama')
                    ->columnSpanFull()
                    ->columns(2)->components([
                        TextInput::make('code')
                            ->label('Kode')
                            ->placeholder('Auto-generate')
                            ->disabled()
                            ->dehydrated(false),
                        TextInput::make('name')
                            ->label('Nama')
                            ->required(),
                        Select::make('category')
                            ->label('Kategori')
                            ->options(LetterCategory::class)
                            ->required(),
                        Textarea::make('body')
                            ->label('Isi')
                            ->required()
                            ->columnSpanFull(),
                        TextInput::make('numbering_pattern')
                            ->label('Pola Nomor'),
                        Toggle::make('requires_signature')
                            ->label('Butuh Tanda Tangan')
                            ->required(),
                        Select::make('default_workflow_id')
                            ->label('Workflow Default')
                            ->relationship('defaultWorkflow', 'name'),
                        Toggle::make('is_active')
                            ->label('Aktif')
                            ->required(),
                    ]),
            ]);
    }
}

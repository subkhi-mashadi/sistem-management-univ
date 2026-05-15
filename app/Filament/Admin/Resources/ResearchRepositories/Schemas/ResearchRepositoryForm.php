<?php

namespace App\Filament\Admin\Resources\ResearchRepositories\Schemas;

use App\Enums\Thesis\ResearchCategory;
use App\Enums\Thesis\ResearchReviewStatus;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ResearchRepositoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->label('Mahasiswa')
                    ->relationship('student', 'id'),
                Select::make('lecturer_id')
                    ->label('Dosen')
                    ->relationship('lecturer', 'id'),
                TextInput::make('title')
                    ->label('Judul')
                    ->required(),
                Textarea::make('abstract')
                    ->label('Abstrak')
                    ->columnSpanFull(),
                TextInput::make('keywords')
                    ->label('Kata Kunci'),
                Select::make('category')
                    ->label('Kategori')
                    ->options(ResearchCategory::class)
                    ->required(),
                TextInput::make('publish_year')
                    ->label('Tahun Terbit'),
                TextInput::make('doi')
                    ->label('DOI'),
                TextInput::make('file_url')
                    ->label('File')
                    ->url(),
                Toggle::make('is_published')
                    ->label('Dipublikasi')
                    ->required(),
                Select::make('review_status')
                    ->label('Status Review')
                    ->options(ResearchReviewStatus::class)
                    ->default('Pending Review')
                    ->required(),
                TextInput::make('reviewed_by')
                    ->label('Direview Oleh')
                    ->numeric(),
            ]);
    }
}

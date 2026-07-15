<?php

namespace App\Filament\Student\Resources\Transcripts\Pages;

use App\Filament\Student\Resources\Transcripts\TranscriptResource;
use Filament\Resources\Pages\ListRecords;

class ListTranscripts extends ListRecords
{
    protected static string $resource = TranscriptResource::class;
}

<?php

namespace App\Filament\Lecturer\Resources\LetterApprovals\Pages;

use App\Filament\Lecturer\Resources\LetterApprovals\LetterApprovalResource;
use Filament\Resources\Pages\ListRecords;

class ListLetterApprovals extends ListRecords
{
    protected static string $resource = LetterApprovalResource::class;
}

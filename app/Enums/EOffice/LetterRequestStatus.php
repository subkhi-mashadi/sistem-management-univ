<?php

namespace App\Enums\EOffice;

use App\Enums\Concerns\HasValues;

enum LetterRequestStatus: string
{
    use HasValues;

    case Draft = 'Draft';
    case InProgress = 'In Progress';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Cancelled = 'Cancelled';
    case Issued = 'Issued';
}

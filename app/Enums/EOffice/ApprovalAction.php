<?php

namespace App\Enums\EOffice;

use App\Enums\Concerns\HasValues;

enum ApprovalAction: string
{
    use HasValues;

    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Delegated = 'Delegated';
}

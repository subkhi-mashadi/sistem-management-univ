<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum ScholarshipRecipientStatus: string
{
    use HasValues;

    case Pending = 'Pending';
    case Active = 'Active';
    case Rejected = 'Rejected';
    case Suspended = 'Suspended';
    case Completed = 'Completed';
    case Revoked = 'Revoked';
}

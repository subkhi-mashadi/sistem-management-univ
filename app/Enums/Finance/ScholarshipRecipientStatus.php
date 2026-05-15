<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum ScholarshipRecipientStatus: string
{
    use HasValues;

    case Active = 'Active';
    case Suspended = 'Suspended';
    case Completed = 'Completed';
    case Revoked = 'Revoked';
}

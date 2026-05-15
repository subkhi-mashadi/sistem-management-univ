<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum LeaveStatus: string
{
    use HasValues;

    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Cancelled = 'Cancelled';
}

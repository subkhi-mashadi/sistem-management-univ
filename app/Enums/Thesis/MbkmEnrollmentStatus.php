<?php

namespace App\Enums\Thesis;

use App\Enums\Concerns\HasValues;

enum MbkmEnrollmentStatus: string
{
    use HasValues;

    case Registered = 'Registered';
    case Approved = 'Approved';
    case Ongoing = 'Ongoing';
    case Completed = 'Completed';
    case Cancelled = 'Cancelled';
}

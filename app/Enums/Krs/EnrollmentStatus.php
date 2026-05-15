<?php

namespace App\Enums\Krs;

use App\Enums\Concerns\HasValues;

enum EnrollmentStatus: string
{
    use HasValues;

    case Draft = 'Draft';
    case Submitted = 'Submitted';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Locked = 'Locked';
}

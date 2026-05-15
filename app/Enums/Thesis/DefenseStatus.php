<?php

namespace App\Enums\Thesis;

use App\Enums\Concerns\HasValues;

enum DefenseStatus: string
{
    use HasValues;

    case Scheduled = 'Scheduled';
    case Ongoing = 'Ongoing';
    case Passed = 'Passed';
    case Failed = 'Failed';
    case Revision = 'Revision';
    case Cancelled = 'Cancelled';
}

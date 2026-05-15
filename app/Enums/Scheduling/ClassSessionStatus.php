<?php

namespace App\Enums\Scheduling;

use App\Enums\Concerns\HasValues;

enum ClassSessionStatus: string
{
    use HasValues;

    case Scheduled = 'Scheduled';
    case Conducted = 'Conducted';
    case Cancelled = 'Cancelled';
    case Substituted = 'Substituted';
}

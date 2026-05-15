<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum TeachingHonorStatus: string
{
    use HasValues;

    case Pending = 'Pending';
    case Approved = 'Approved';
    case Paid = 'Paid';
    case Cancelled = 'Cancelled';
}

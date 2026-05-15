<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum PayrollPeriodStatus: string
{
    use HasValues;

    case Open = 'Open';
    case Calculating = 'Calculating';
    case Calculated = 'Calculated';
    case Approved = 'Approved';
    case Paid = 'Paid';
    case Closed = 'Closed';
}

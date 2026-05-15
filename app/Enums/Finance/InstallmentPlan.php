<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum InstallmentPlan: string
{
    use HasValues;

    case Full = 'Full';
    case Twice = '2x';
    case Thrice = '3x';
}

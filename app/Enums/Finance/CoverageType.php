<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum CoverageType: string
{
    use HasValues;

    case Full = 'Full';
    case PartialPercent = 'Partial Percent';
    case PartialAmount = 'Partial Amount';
}

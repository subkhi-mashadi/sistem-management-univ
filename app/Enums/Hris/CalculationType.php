<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum CalculationType: string
{
    use HasValues;

    case Fixed = 'Fixed';
    case Percentage = 'Percentage';
    case Formula = 'Formula';
    case Manual = 'Manual';
}

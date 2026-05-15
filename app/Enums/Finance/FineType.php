<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum FineType: string
{
    use HasValues;

    case Late = 'Late';
    case Library = 'Library';
    case Other = 'Other';
}

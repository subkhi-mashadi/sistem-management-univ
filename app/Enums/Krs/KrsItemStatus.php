<?php

namespace App\Enums\Krs;

use App\Enums\Concerns\HasValues;

enum KrsItemStatus: string
{
    use HasValues;

    case Active = 'Active';
    case Dropped = 'Dropped';
    case Failed = 'Failed';
    case Passed = 'Passed';
}

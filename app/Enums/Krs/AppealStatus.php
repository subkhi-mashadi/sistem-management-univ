<?php

namespace App\Enums\Krs;

use App\Enums\Concerns\HasValues;

enum AppealStatus: string
{
    use HasValues;

    case Pending = 'Pending';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
}

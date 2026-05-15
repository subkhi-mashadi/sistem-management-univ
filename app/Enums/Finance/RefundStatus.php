<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum RefundStatus: string
{
    use HasValues;

    case Requested = 'Requested';
    case Approved = 'Approved';
    case Rejected = 'Rejected';
    case Disbursed = 'Disbursed';
}

<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum PaymentStatus: string
{
    use HasValues;

    case Pending = 'Pending';
    case Success = 'Success';
    case Failed = 'Failed';
    case Refunded = 'Refunded';
}

<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum RefundReason: string
{
    use HasValues;

    case Cuti = 'Cuti';
    case Mundur = 'Mundur';
    case Overpayment = 'Overpayment';
    case Lainnya = 'Lainnya';
}

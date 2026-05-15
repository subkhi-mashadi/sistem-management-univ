<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum PaymentMethod: string
{
    use HasValues;

    case VA = 'VA';
    case Transfer = 'Transfer';
    case Cash = 'Cash';
    case EDC = 'EDC';
    case EWallet = 'EWallet';
}

<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum InvoiceStatus: string
{
    use HasValues;

    case Unpaid = 'Unpaid';
    case Partial = 'Partial';
    case Paid = 'Paid';
    case Overdue = 'Overdue';
    case Cancelled = 'Cancelled';
}

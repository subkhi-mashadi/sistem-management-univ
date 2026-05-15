<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum SalaryStatus: string
{
    use HasValues;

    case Draft = 'Draft';
    case Calculated = 'Calculated';
    case Approved = 'Approved';
    case Paid = 'Paid';
}

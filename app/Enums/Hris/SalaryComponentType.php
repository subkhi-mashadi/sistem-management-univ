<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum SalaryComponentType: string
{
    use HasValues;

    case Earning = 'Earning';
    case Deduction = 'Deduction';
    case Tax = 'Tax';
    case BPJS = 'BPJS';
}

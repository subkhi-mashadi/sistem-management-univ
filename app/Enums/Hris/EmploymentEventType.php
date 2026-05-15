<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum EmploymentEventType: string
{
    use HasValues;

    case Hire = 'Hire';
    case Promotion = 'Promotion';
    case Demotion = 'Demotion';
    case Transfer = 'Transfer';
    case RankChange = 'Rank Change';
    case SalaryAdjustment = 'Salary Adjustment';
    case Resignation = 'Resignation';
    case Retirement = 'Retirement';
}

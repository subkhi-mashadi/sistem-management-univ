<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum EmployeeStatus: string
{
    use HasValues;

    case Active = 'Active';
    case OnLeave = 'On Leave';
    case Resigned = 'Resigned';
    case Retired = 'Retired';
    case Terminated = 'Terminated';
}

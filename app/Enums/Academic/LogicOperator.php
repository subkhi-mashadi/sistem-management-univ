<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum LogicOperator: string
{
    use HasValues;

    case AndOp = 'AND';
    case OrOp = 'OR';
}

<?php

namespace App\Enums\Thesis;

use App\Enums\Concerns\HasValues;

enum ThesisAdvisorStatus: string
{
    use HasValues;

    case Active = 'Active';
    case Replaced = 'Replaced';
    case Completed = 'Completed';
}

<?php

namespace App\Enums\Scheduling;

use App\Enums\Concerns\HasValues;

enum ExamType: string
{
    use HasValues;

    case UTS = 'UTS';
    case UAS = 'UAS';
    case Quiz = 'Quiz';
    case Susulan = 'Susulan';
}

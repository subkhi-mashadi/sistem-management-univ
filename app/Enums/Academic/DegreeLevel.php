<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum DegreeLevel: string
{
    use HasValues;

    case D3 = 'D3';
    case D4 = 'D4';
    case S1 = 'S1';
    case S2 = 'S2';
    case S3 = 'S3';
    case Profesi = 'Profesi';
}

<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum EducationLevel: string
{
    use HasValues;

    case S2 = 'S2';
    case S3 = 'S3';
}

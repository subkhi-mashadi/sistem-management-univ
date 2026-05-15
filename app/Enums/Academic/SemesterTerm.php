<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum SemesterTerm: string
{
    use HasValues;

    case Ganjil = 'Ganjil';
    case Genap = 'Genap';
    case Pendek = 'Pendek';
}

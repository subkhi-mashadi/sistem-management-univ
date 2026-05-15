<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum Accreditation: string
{
    use HasValues;

    case A = 'A';
    case B = 'B';
    case C = 'C';
    case Unggul = 'Unggul';
    case BaikSekali = 'Baik Sekali';
    case Baik = 'Baik';
    case TidakTerakreditasi = 'Tidak Terakreditasi';
}

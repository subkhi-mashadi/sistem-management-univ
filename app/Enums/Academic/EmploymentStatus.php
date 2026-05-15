<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum EmploymentStatus: string
{
    use HasValues;

    case PNS = 'PNS';
    case TetapYayasan = 'Tetap Yayasan';
    case Kontrak = 'Kontrak';
    case Honorer = 'Honorer';
    case Tamu = 'Tamu';
}

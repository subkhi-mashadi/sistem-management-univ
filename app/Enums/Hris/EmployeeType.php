<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum EmployeeType: string
{
    use HasValues;

    case PNS = 'PNS';
    case TetapYayasan = 'Tetap Yayasan';
    case Kontrak = 'Kontrak';
    case Honorer = 'Honorer';
    case Tamu = 'Tamu';
}

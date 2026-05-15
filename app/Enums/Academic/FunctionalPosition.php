<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum FunctionalPosition: string
{
    use HasValues;

    case AsistenAhli = 'Asisten Ahli';
    case Lektor = 'Lektor';
    case LektorKepala = 'Lektor Kepala';
    case Profesor = 'Profesor';
    case TenagaPengajar = 'Tenaga Pengajar';
}

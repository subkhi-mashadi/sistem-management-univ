<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum StudentStatus: string
{
    use HasValues;

    case Aktif = 'Aktif';
    case Cuti = 'Cuti';
    case Lulus = 'Lulus';
    case DO = 'DO';
    case Mundur = 'Mundur';
    case NonAktif = 'Non-Aktif';
}

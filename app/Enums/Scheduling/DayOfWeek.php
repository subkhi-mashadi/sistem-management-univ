<?php

namespace App\Enums\Scheduling;

use App\Enums\Concerns\HasValues;

enum DayOfWeek: string
{
    use HasValues;

    case Senin = 'Senin';
    case Selasa = 'Selasa';
    case Rabu = 'Rabu';
    case Kamis = 'Kamis';
    case Jumat = 'Jumat';
    case Sabtu = 'Sabtu';
    case Minggu = 'Minggu';
}

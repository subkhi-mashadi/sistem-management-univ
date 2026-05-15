<?php

namespace App\Enums\Scheduling;

use App\Enums\Concerns\HasValues;

enum AttendanceStatus: string
{
    use HasValues;

    case Hadir = 'Hadir';
    case Sakit = 'Sakit';
    case Izin = 'Izin';
    case Alpa = 'Alpa';
    case Terlambat = 'Terlambat';
}

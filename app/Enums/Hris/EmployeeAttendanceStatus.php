<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum EmployeeAttendanceStatus: string
{
    use HasValues;

    case Hadir = 'Hadir';
    case Sakit = 'Sakit';
    case Izin = 'Izin';
    case Cuti = 'Cuti';
    case Alpa = 'Alpa';
    case DinasLuar = 'Dinas Luar';
    case WFH = 'WFH';
}

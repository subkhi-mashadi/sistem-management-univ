<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum LeaveType: string
{
    use HasValues;

    case Tahunan = 'Tahunan';
    case Sakit = 'Sakit';
    case Besar = 'Besar';
    case Melahirkan = 'Melahirkan';
    case Menikah = 'Menikah';
    case Ibadah = 'Ibadah';
    case TanpaGaji = 'Tanpa Gaji';
    case Khusus = 'Khusus';
}

<?php

namespace App\Enums\Krs;

use App\Enums\Concerns\HasValues;

enum AcademicStatus: string
{
    use HasValues;

    case Normal = 'Normal';
    case Peringatan = 'Peringatan';
    case Percobaan = 'Percobaan';
    case DoRisk = 'DO Risk';
}

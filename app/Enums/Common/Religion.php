<?php

namespace App\Enums\Common;

use App\Enums\Concerns\HasValues;

enum Religion: string
{
    use HasValues;

    case Islam = 'Islam';
    case KristenProtestan = 'Kristen Protestan';
    case Katolik = 'Katolik';
    case Hindu = 'Hindu';
    case Buddha = 'Buddha';
    case Konghucu = 'Konghucu';
    case PenghayatKepercayaan = 'Penghayat Kepercayaan';
}

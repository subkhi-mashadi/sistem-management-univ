<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum CourseType: string
{
    use HasValues;

    case Wajib = 'Wajib';
    case Pilihan = 'Pilihan';
    case MKDU = 'MKDU';
    case MKWU = 'MKWU';
    case Konsentrasi = 'Konsentrasi';
}

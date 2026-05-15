<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum EntryPath: string
{
    use HasValues;

    case SNBP = 'SNBP';
    case SNBT = 'SNBT';
    case Mandiri = 'Mandiri';
    case Pindahan = 'Pindahan';
    case Transfer = 'Transfer';
    case Kerjasama = 'Kerjasama';
}

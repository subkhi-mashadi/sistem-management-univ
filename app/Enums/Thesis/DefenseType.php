<?php

namespace App\Enums\Thesis;

use App\Enums\Concerns\HasValues;

enum DefenseType: string
{
    use HasValues;

    case Proposal = 'Proposal';
    case Hasil = 'Hasil';
    case Tutup = 'Tutup';
}

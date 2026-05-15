<?php

namespace App\Enums\Thesis;

use App\Enums\Concerns\HasValues;

enum ResearchCategory: string
{
    use HasValues;

    case Skripsi = 'Skripsi';
    case Tesis = 'Tesis';
    case Disertasi = 'Disertasi';
    case Jurnal = 'Jurnal';
    case Prosiding = 'Prosiding';
    case Buku = 'Buku';
    case Lainnya = 'Lainnya';
}

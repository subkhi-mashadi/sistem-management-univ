<?php

namespace App\Enums\Thesis;

use App\Enums\Concerns\HasValues;

enum MbkmType: string
{
    use HasValues;

    case Magang = 'Magang';
    case Pertukaran = 'Pertukaran';
    case Riset = 'Riset';
    case KKN = 'KKN';
    case Mengajar = 'Mengajar';
    case Wirausaha = 'Wirausaha';
    case ProyekKemanusiaan = 'Proyek Kemanusiaan';
    case StudiIndependen = 'Studi Independen';
}

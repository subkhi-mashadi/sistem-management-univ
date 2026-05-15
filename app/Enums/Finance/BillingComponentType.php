<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum BillingComponentType: string
{
    use HasValues;

    case UKT = 'UKT';
    case SPP = 'SPP';
    case Pengembangan = 'Pengembangan';
    case Praktikum = 'Praktikum';
    case KKN = 'KKN';
    case Wisuda = 'Wisuda';
    case Skripsi = 'Skripsi';
    case Denda = 'Denda';
    case Lainnya = 'Lainnya';
}

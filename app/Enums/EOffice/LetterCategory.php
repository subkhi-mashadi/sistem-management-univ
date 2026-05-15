<?php

namespace App\Enums\EOffice;

use App\Enums\Concerns\HasValues;

enum LetterCategory: string
{
    use HasValues;

    case Cuti = 'Cuti';
    case AktifKuliah = 'Aktif Kuliah';
    case Magang = 'Magang';
    case Rekomendasi = 'Rekomendasi';
    case Yudisium = 'Yudisium';
    case BebasPustaka = 'Bebas Pustaka';
    case Pengantar = 'Pengantar';
    case Lainnya = 'Lainnya';
}

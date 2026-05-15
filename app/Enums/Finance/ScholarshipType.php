<?php

namespace App\Enums\Finance;

use App\Enums\Concerns\HasValues;

enum ScholarshipType: string
{
    use HasValues;

    case KipK = 'KIP-K';
    case Bidikmisi = 'Bidikmisi';
    case Prestasi = 'Prestasi';
    case AnakPegawai = 'Anak Pegawai';
    case Yayasan = 'Yayasan';
    case Eksternal = 'Eksternal';
    case Lainnya = 'Lainnya';
}

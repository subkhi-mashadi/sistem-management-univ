<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum StructuralPosition: string
{
    use HasValues;

    case Rektor = 'Rektor';
    case WakilRektor1 = 'Wakil Rektor I';
    case WakilRektor2 = 'Wakil Rektor II';
    case WakilRektor3 = 'Wakil Rektor III';
    case Dekan = 'Dekan';
    case WakilDekan1 = 'Wakil Dekan I';
    case WakilDekan2 = 'Wakil Dekan II';
    case WakilDekan3 = 'Wakil Dekan III';
    case Kaprodi = 'Kaprodi';
    case Sekprodi = 'Sekprodi';
    case KepalaLab = 'Kepala Laboratorium';
    case KetuaUnit = 'Ketua Unit';
    case None = 'Tidak Ada';
}

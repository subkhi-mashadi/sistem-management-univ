<?php

namespace App\Enums\Academic;

use App\Enums\Concerns\HasValues;

enum RoomType: string
{
    use HasValues;

    case Kelas = 'Kelas';
    case Lab = 'Lab';
    case Studio = 'Studio';
    case Auditorium = 'Auditorium';
    case Online = 'Online';
}

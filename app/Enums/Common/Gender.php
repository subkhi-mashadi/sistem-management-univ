<?php

namespace App\Enums\Common;

use App\Enums\Concerns\HasValues;

enum Gender: string
{
    use HasValues;

    case L = 'L';
    case P = 'P';
}

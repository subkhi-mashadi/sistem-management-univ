<?php

namespace App\Enums\Scheduling;

use App\Enums\Concerns\HasValues;

enum CheckMethod: string
{
    use HasValues;

    case QR = 'QR';
    case Fingerprint = 'Fingerprint';
    case Face = 'Face';
    case Manual = 'Manual';
}

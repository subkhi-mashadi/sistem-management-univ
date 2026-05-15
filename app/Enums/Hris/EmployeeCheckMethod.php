<?php

namespace App\Enums\Hris;

use App\Enums\Concerns\HasValues;

enum EmployeeCheckMethod: string
{
    use HasValues;

    case Fingerprint = 'Fingerprint';
    case QR = 'QR';
    case Face = 'Face';
    case Mobile = 'Mobile';
    case Manual = 'Manual';
}

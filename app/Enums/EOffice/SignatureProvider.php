<?php

namespace App\Enums\EOffice;

use App\Enums\Concerns\HasValues;

enum SignatureProvider: string
{
    use HasValues;

    case BSrE = 'BSrE';
    case PrivyID = 'PrivyID';
    case VIDA = 'VIDA';
    case Internal = 'Internal';
}

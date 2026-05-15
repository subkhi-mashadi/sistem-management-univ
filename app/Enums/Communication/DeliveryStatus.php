<?php

namespace App\Enums\Communication;

use App\Enums\Concerns\HasValues;

enum DeliveryStatus: string
{
    use HasValues;

    case Queued = 'Queued';
    case Sent = 'Sent';
    case Delivered = 'Delivered';
    case Read = 'Read';
    case Failed = 'Failed';
    case Bounced = 'Bounced';
}

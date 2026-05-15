<?php

namespace App\Enums\Communication;

use App\Enums\Concerns\HasValues;

enum AnnouncementStatus: string
{
    use HasValues;

    case Draft = 'Draft';
    case Scheduled = 'Scheduled';
    case Sending = 'Sending';
    case Sent = 'Sent';
    case Failed = 'Failed';
    case Cancelled = 'Cancelled';
}

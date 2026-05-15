<?php

namespace App\Enums\Communication;

use App\Enums\Concerns\HasValues;

enum NotificationChannel: string
{
    use HasValues;

    case Email = 'email';
    case Wa = 'wa';
    case Push = 'push';
    case Inapp = 'inapp';
}

<?php

namespace App\Enums\Rbac;

use App\Enums\Concerns\HasValues;

enum PermissionAction: string
{
    use HasValues;

    case View = 'view';
    case Create = 'create';
    case Edit = 'edit';
    case Delete = 'delete';
    case Approve = 'approve';
    case Export = 'export';
}

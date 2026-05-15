<?php

namespace App\Enums\Rbac;

use App\Enums\Concerns\HasValues;

enum PermissionModule: string
{
    use HasValues;

    case Rbac = 'rbac';
    case Academic = 'academic';
    case Scheduling = 'scheduling';
    case Krs = 'krs';
    case Finance = 'finance';
    case EOffice = 'e-office';
    case Thesis = 'thesis';
    case Communication = 'communication';
    case Hris = 'hris';
    case Reporting = 'reporting';
}

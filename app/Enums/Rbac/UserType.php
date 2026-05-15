<?php

namespace App\Enums\Rbac;

use App\Enums\Concerns\HasValues;

enum UserType: string
{
    use HasValues;

    case Student = 'student';
    case Lecturer = 'lecturer';
    case Staff = 'staff';
    case Admin = 'admin';
}

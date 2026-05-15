<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\AcademicCalendar;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcademicCalendarPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:AcademicCalendar');
    }

    public function view(AuthUser $authUser, AcademicCalendar $academicCalendar): bool
    {
        return $authUser->can('View:AcademicCalendar');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:AcademicCalendar');
    }

    public function update(AuthUser $authUser, AcademicCalendar $academicCalendar): bool
    {
        return $authUser->can('Update:AcademicCalendar');
    }

    public function delete(AuthUser $authUser, AcademicCalendar $academicCalendar): bool
    {
        return $authUser->can('Delete:AcademicCalendar');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:AcademicCalendar');
    }

    public function restore(AuthUser $authUser, AcademicCalendar $academicCalendar): bool
    {
        return $authUser->can('Restore:AcademicCalendar');
    }

    public function forceDelete(AuthUser $authUser, AcademicCalendar $academicCalendar): bool
    {
        return $authUser->can('ForceDelete:AcademicCalendar');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:AcademicCalendar');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:AcademicCalendar');
    }

    public function replicate(AuthUser $authUser, AcademicCalendar $academicCalendar): bool
    {
        return $authUser->can('Replicate:AcademicCalendar');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:AcademicCalendar');
    }

}
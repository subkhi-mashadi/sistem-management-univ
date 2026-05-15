<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MbkmEnrollment;
use Illuminate\Auth\Access\HandlesAuthorization;

class MbkmEnrollmentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MbkmEnrollment');
    }

    public function view(AuthUser $authUser, MbkmEnrollment $mbkmEnrollment): bool
    {
        return $authUser->can('View:MbkmEnrollment');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MbkmEnrollment');
    }

    public function update(AuthUser $authUser, MbkmEnrollment $mbkmEnrollment): bool
    {
        return $authUser->can('Update:MbkmEnrollment');
    }

    public function delete(AuthUser $authUser, MbkmEnrollment $mbkmEnrollment): bool
    {
        return $authUser->can('Delete:MbkmEnrollment');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MbkmEnrollment');
    }

    public function restore(AuthUser $authUser, MbkmEnrollment $mbkmEnrollment): bool
    {
        return $authUser->can('Restore:MbkmEnrollment');
    }

    public function forceDelete(AuthUser $authUser, MbkmEnrollment $mbkmEnrollment): bool
    {
        return $authUser->can('ForceDelete:MbkmEnrollment');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MbkmEnrollment');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MbkmEnrollment');
    }

    public function replicate(AuthUser $authUser, MbkmEnrollment $mbkmEnrollment): bool
    {
        return $authUser->can('Replicate:MbkmEnrollment');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MbkmEnrollment');
    }

}
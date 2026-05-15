<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LecturerWorkload;
use Illuminate\Auth\Access\HandlesAuthorization;

class LecturerWorkloadPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LecturerWorkload');
    }

    public function view(AuthUser $authUser, LecturerWorkload $lecturerWorkload): bool
    {
        return $authUser->can('View:LecturerWorkload');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LecturerWorkload');
    }

    public function update(AuthUser $authUser, LecturerWorkload $lecturerWorkload): bool
    {
        return $authUser->can('Update:LecturerWorkload');
    }

    public function delete(AuthUser $authUser, LecturerWorkload $lecturerWorkload): bool
    {
        return $authUser->can('Delete:LecturerWorkload');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LecturerWorkload');
    }

    public function restore(AuthUser $authUser, LecturerWorkload $lecturerWorkload): bool
    {
        return $authUser->can('Restore:LecturerWorkload');
    }

    public function forceDelete(AuthUser $authUser, LecturerWorkload $lecturerWorkload): bool
    {
        return $authUser->can('ForceDelete:LecturerWorkload');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LecturerWorkload');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LecturerWorkload');
    }

    public function replicate(AuthUser $authUser, LecturerWorkload $lecturerWorkload): bool
    {
        return $authUser->can('Replicate:LecturerWorkload');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LecturerWorkload');
    }

}
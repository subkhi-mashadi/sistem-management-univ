<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TeachingHonor;
use Illuminate\Auth\Access\HandlesAuthorization;

class TeachingHonorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TeachingHonor');
    }

    public function view(AuthUser $authUser, TeachingHonor $teachingHonor): bool
    {
        return $authUser->can('View:TeachingHonor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TeachingHonor');
    }

    public function update(AuthUser $authUser, TeachingHonor $teachingHonor): bool
    {
        return $authUser->can('Update:TeachingHonor');
    }

    public function delete(AuthUser $authUser, TeachingHonor $teachingHonor): bool
    {
        return $authUser->can('Delete:TeachingHonor');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TeachingHonor');
    }

    public function restore(AuthUser $authUser, TeachingHonor $teachingHonor): bool
    {
        return $authUser->can('Restore:TeachingHonor');
    }

    public function forceDelete(AuthUser $authUser, TeachingHonor $teachingHonor): bool
    {
        return $authUser->can('ForceDelete:TeachingHonor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TeachingHonor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TeachingHonor');
    }

    public function replicate(AuthUser $authUser, TeachingHonor $teachingHonor): bool
    {
        return $authUser->can('Replicate:TeachingHonor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TeachingHonor');
    }

}
<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\Prerequisite;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrerequisitePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:Prerequisite');
    }

    public function view(AuthUser $authUser, Prerequisite $prerequisite): bool
    {
        return $authUser->can('View:Prerequisite');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:Prerequisite');
    }

    public function update(AuthUser $authUser, Prerequisite $prerequisite): bool
    {
        return $authUser->can('Update:Prerequisite');
    }

    public function delete(AuthUser $authUser, Prerequisite $prerequisite): bool
    {
        return $authUser->can('Delete:Prerequisite');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:Prerequisite');
    }

    public function restore(AuthUser $authUser, Prerequisite $prerequisite): bool
    {
        return $authUser->can('Restore:Prerequisite');
    }

    public function forceDelete(AuthUser $authUser, Prerequisite $prerequisite): bool
    {
        return $authUser->can('ForceDelete:Prerequisite');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:Prerequisite');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:Prerequisite');
    }

    public function replicate(AuthUser $authUser, Prerequisite $prerequisite): bool
    {
        return $authUser->can('Replicate:Prerequisite');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:Prerequisite');
    }

}
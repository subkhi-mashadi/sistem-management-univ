<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\UktGroup;
use Illuminate\Auth\Access\HandlesAuthorization;

class UktGroupPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:UktGroup');
    }

    public function view(AuthUser $authUser, UktGroup $uktGroup): bool
    {
        return $authUser->can('View:UktGroup');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:UktGroup');
    }

    public function update(AuthUser $authUser, UktGroup $uktGroup): bool
    {
        return $authUser->can('Update:UktGroup');
    }

    public function delete(AuthUser $authUser, UktGroup $uktGroup): bool
    {
        return $authUser->can('Delete:UktGroup');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:UktGroup');
    }

    public function restore(AuthUser $authUser, UktGroup $uktGroup): bool
    {
        return $authUser->can('Restore:UktGroup');
    }

    public function forceDelete(AuthUser $authUser, UktGroup $uktGroup): bool
    {
        return $authUser->can('ForceDelete:UktGroup');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:UktGroup');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:UktGroup');
    }

    public function replicate(AuthUser $authUser, UktGroup $uktGroup): bool
    {
        return $authUser->can('Replicate:UktGroup');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:UktGroup');
    }

}
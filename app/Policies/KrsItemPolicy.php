<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\KrsItem;
use Illuminate\Auth\Access\HandlesAuthorization;

class KrsItemPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:KrsItem');
    }

    public function view(AuthUser $authUser, KrsItem $krsItem): bool
    {
        return $authUser->can('View:KrsItem');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:KrsItem');
    }

    public function update(AuthUser $authUser, KrsItem $krsItem): bool
    {
        return $authUser->can('Update:KrsItem');
    }

    public function delete(AuthUser $authUser, KrsItem $krsItem): bool
    {
        return $authUser->can('Delete:KrsItem');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:KrsItem');
    }

    public function restore(AuthUser $authUser, KrsItem $krsItem): bool
    {
        return $authUser->can('Restore:KrsItem');
    }

    public function forceDelete(AuthUser $authUser, KrsItem $krsItem): bool
    {
        return $authUser->can('ForceDelete:KrsItem');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:KrsItem');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:KrsItem');
    }

    public function replicate(AuthUser $authUser, KrsItem $krsItem): bool
    {
        return $authUser->can('Replicate:KrsItem');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:KrsItem');
    }

}
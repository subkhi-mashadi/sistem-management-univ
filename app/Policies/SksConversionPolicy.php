<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SksConversion;
use Illuminate\Auth\Access\HandlesAuthorization;

class SksConversionPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SksConversion');
    }

    public function view(AuthUser $authUser, SksConversion $sksConversion): bool
    {
        return $authUser->can('View:SksConversion');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SksConversion');
    }

    public function update(AuthUser $authUser, SksConversion $sksConversion): bool
    {
        return $authUser->can('Update:SksConversion');
    }

    public function delete(AuthUser $authUser, SksConversion $sksConversion): bool
    {
        return $authUser->can('Delete:SksConversion');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SksConversion');
    }

    public function restore(AuthUser $authUser, SksConversion $sksConversion): bool
    {
        return $authUser->can('Restore:SksConversion');
    }

    public function forceDelete(AuthUser $authUser, SksConversion $sksConversion): bool
    {
        return $authUser->can('ForceDelete:SksConversion');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SksConversion');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SksConversion');
    }

    public function replicate(AuthUser $authUser, SksConversion $sksConversion): bool
    {
        return $authUser->can('Replicate:SksConversion');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SksConversion');
    }

}
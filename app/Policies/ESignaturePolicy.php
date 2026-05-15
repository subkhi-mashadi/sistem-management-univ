<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ESignature;
use Illuminate\Auth\Access\HandlesAuthorization;

class ESignaturePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ESignature');
    }

    public function view(AuthUser $authUser, ESignature $eSignature): bool
    {
        return $authUser->can('View:ESignature');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ESignature');
    }

    public function update(AuthUser $authUser, ESignature $eSignature): bool
    {
        return $authUser->can('Update:ESignature');
    }

    public function delete(AuthUser $authUser, ESignature $eSignature): bool
    {
        return $authUser->can('Delete:ESignature');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ESignature');
    }

    public function restore(AuthUser $authUser, ESignature $eSignature): bool
    {
        return $authUser->can('Restore:ESignature');
    }

    public function forceDelete(AuthUser $authUser, ESignature $eSignature): bool
    {
        return $authUser->can('ForceDelete:ESignature');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ESignature');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ESignature');
    }

    public function replicate(AuthUser $authUser, ESignature $eSignature): bool
    {
        return $authUser->can('Replicate:ESignature');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ESignature');
    }

}
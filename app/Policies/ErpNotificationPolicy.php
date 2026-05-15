<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ErpNotification;
use Illuminate\Auth\Access\HandlesAuthorization;

class ErpNotificationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ErpNotification');
    }

    public function view(AuthUser $authUser, ErpNotification $erpNotification): bool
    {
        return $authUser->can('View:ErpNotification');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ErpNotification');
    }

    public function update(AuthUser $authUser, ErpNotification $erpNotification): bool
    {
        return $authUser->can('Update:ErpNotification');
    }

    public function delete(AuthUser $authUser, ErpNotification $erpNotification): bool
    {
        return $authUser->can('Delete:ErpNotification');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ErpNotification');
    }

    public function restore(AuthUser $authUser, ErpNotification $erpNotification): bool
    {
        return $authUser->can('Restore:ErpNotification');
    }

    public function forceDelete(AuthUser $authUser, ErpNotification $erpNotification): bool
    {
        return $authUser->can('ForceDelete:ErpNotification');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ErpNotification');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ErpNotification');
    }

    public function replicate(AuthUser $authUser, ErpNotification $erpNotification): bool
    {
        return $authUser->can('Replicate:ErpNotification');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ErpNotification');
    }

}
<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\DeliveryLog;
use Illuminate\Auth\Access\HandlesAuthorization;

class DeliveryLogPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:DeliveryLog');
    }

    public function view(AuthUser $authUser, DeliveryLog $deliveryLog): bool
    {
        return $authUser->can('View:DeliveryLog');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:DeliveryLog');
    }

    public function update(AuthUser $authUser, DeliveryLog $deliveryLog): bool
    {
        return $authUser->can('Update:DeliveryLog');
    }

    public function delete(AuthUser $authUser, DeliveryLog $deliveryLog): bool
    {
        return $authUser->can('Delete:DeliveryLog');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:DeliveryLog');
    }

    public function restore(AuthUser $authUser, DeliveryLog $deliveryLog): bool
    {
        return $authUser->can('Restore:DeliveryLog');
    }

    public function forceDelete(AuthUser $authUser, DeliveryLog $deliveryLog): bool
    {
        return $authUser->can('ForceDelete:DeliveryLog');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:DeliveryLog');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:DeliveryLog');
    }

    public function replicate(AuthUser $authUser, DeliveryLog $deliveryLog): bool
    {
        return $authUser->can('Replicate:DeliveryLog');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:DeliveryLog');
    }

}
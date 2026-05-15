<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BillingRate;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillingRatePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BillingRate');
    }

    public function view(AuthUser $authUser, BillingRate $billingRate): bool
    {
        return $authUser->can('View:BillingRate');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BillingRate');
    }

    public function update(AuthUser $authUser, BillingRate $billingRate): bool
    {
        return $authUser->can('Update:BillingRate');
    }

    public function delete(AuthUser $authUser, BillingRate $billingRate): bool
    {
        return $authUser->can('Delete:BillingRate');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BillingRate');
    }

    public function restore(AuthUser $authUser, BillingRate $billingRate): bool
    {
        return $authUser->can('Restore:BillingRate');
    }

    public function forceDelete(AuthUser $authUser, BillingRate $billingRate): bool
    {
        return $authUser->can('ForceDelete:BillingRate');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BillingRate');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BillingRate');
    }

    public function replicate(AuthUser $authUser, BillingRate $billingRate): bool
    {
        return $authUser->can('Replicate:BillingRate');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BillingRate');
    }

}
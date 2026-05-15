<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\BillingComponent;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillingComponentPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:BillingComponent');
    }

    public function view(AuthUser $authUser, BillingComponent $billingComponent): bool
    {
        return $authUser->can('View:BillingComponent');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:BillingComponent');
    }

    public function update(AuthUser $authUser, BillingComponent $billingComponent): bool
    {
        return $authUser->can('Update:BillingComponent');
    }

    public function delete(AuthUser $authUser, BillingComponent $billingComponent): bool
    {
        return $authUser->can('Delete:BillingComponent');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:BillingComponent');
    }

    public function restore(AuthUser $authUser, BillingComponent $billingComponent): bool
    {
        return $authUser->can('Restore:BillingComponent');
    }

    public function forceDelete(AuthUser $authUser, BillingComponent $billingComponent): bool
    {
        return $authUser->can('ForceDelete:BillingComponent');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:BillingComponent');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:BillingComponent');
    }

    public function replicate(AuthUser $authUser, BillingComponent $billingComponent): bool
    {
        return $authUser->can('Replicate:BillingComponent');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:BillingComponent');
    }

}
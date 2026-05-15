<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\TaxCalculation;
use Illuminate\Auth\Access\HandlesAuthorization;

class TaxCalculationPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:TaxCalculation');
    }

    public function view(AuthUser $authUser, TaxCalculation $taxCalculation): bool
    {
        return $authUser->can('View:TaxCalculation');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:TaxCalculation');
    }

    public function update(AuthUser $authUser, TaxCalculation $taxCalculation): bool
    {
        return $authUser->can('Update:TaxCalculation');
    }

    public function delete(AuthUser $authUser, TaxCalculation $taxCalculation): bool
    {
        return $authUser->can('Delete:TaxCalculation');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:TaxCalculation');
    }

    public function restore(AuthUser $authUser, TaxCalculation $taxCalculation): bool
    {
        return $authUser->can('Restore:TaxCalculation');
    }

    public function forceDelete(AuthUser $authUser, TaxCalculation $taxCalculation): bool
    {
        return $authUser->can('ForceDelete:TaxCalculation');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:TaxCalculation');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:TaxCalculation');
    }

    public function replicate(AuthUser $authUser, TaxCalculation $taxCalculation): bool
    {
        return $authUser->can('Replicate:TaxCalculation');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:TaxCalculation');
    }

}
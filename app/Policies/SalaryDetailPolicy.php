<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\SalaryDetail;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalaryDetailPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:SalaryDetail');
    }

    public function view(AuthUser $authUser, SalaryDetail $salaryDetail): bool
    {
        return $authUser->can('View:SalaryDetail');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:SalaryDetail');
    }

    public function update(AuthUser $authUser, SalaryDetail $salaryDetail): bool
    {
        return $authUser->can('Update:SalaryDetail');
    }

    public function delete(AuthUser $authUser, SalaryDetail $salaryDetail): bool
    {
        return $authUser->can('Delete:SalaryDetail');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:SalaryDetail');
    }

    public function restore(AuthUser $authUser, SalaryDetail $salaryDetail): bool
    {
        return $authUser->can('Restore:SalaryDetail');
    }

    public function forceDelete(AuthUser $authUser, SalaryDetail $salaryDetail): bool
    {
        return $authUser->can('ForceDelete:SalaryDetail');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:SalaryDetail');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:SalaryDetail');
    }

    public function replicate(AuthUser $authUser, SalaryDetail $salaryDetail): bool
    {
        return $authUser->can('Replicate:SalaryDetail');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:SalaryDetail');
    }

}
<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\EmploymentHistory;
use Illuminate\Auth\Access\HandlesAuthorization;

class EmploymentHistoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:EmploymentHistory');
    }

    public function view(AuthUser $authUser, EmploymentHistory $employmentHistory): bool
    {
        return $authUser->can('View:EmploymentHistory');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:EmploymentHistory');
    }

    public function update(AuthUser $authUser, EmploymentHistory $employmentHistory): bool
    {
        return $authUser->can('Update:EmploymentHistory');
    }

    public function delete(AuthUser $authUser, EmploymentHistory $employmentHistory): bool
    {
        return $authUser->can('Delete:EmploymentHistory');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:EmploymentHistory');
    }

    public function restore(AuthUser $authUser, EmploymentHistory $employmentHistory): bool
    {
        return $authUser->can('Restore:EmploymentHistory');
    }

    public function forceDelete(AuthUser $authUser, EmploymentHistory $employmentHistory): bool
    {
        return $authUser->can('ForceDelete:EmploymentHistory');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:EmploymentHistory');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:EmploymentHistory');
    }

    public function replicate(AuthUser $authUser, EmploymentHistory $employmentHistory): bool
    {
        return $authUser->can('Replicate:EmploymentHistory');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:EmploymentHistory');
    }

}
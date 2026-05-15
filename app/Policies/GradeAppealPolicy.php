<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\GradeAppeal;
use Illuminate\Auth\Access\HandlesAuthorization;

class GradeAppealPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:GradeAppeal');
    }

    public function view(AuthUser $authUser, GradeAppeal $gradeAppeal): bool
    {
        return $authUser->can('View:GradeAppeal');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:GradeAppeal');
    }

    public function update(AuthUser $authUser, GradeAppeal $gradeAppeal): bool
    {
        return $authUser->can('Update:GradeAppeal');
    }

    public function delete(AuthUser $authUser, GradeAppeal $gradeAppeal): bool
    {
        return $authUser->can('Delete:GradeAppeal');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:GradeAppeal');
    }

    public function restore(AuthUser $authUser, GradeAppeal $gradeAppeal): bool
    {
        return $authUser->can('Restore:GradeAppeal');
    }

    public function forceDelete(AuthUser $authUser, GradeAppeal $gradeAppeal): bool
    {
        return $authUser->can('ForceDelete:GradeAppeal');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:GradeAppeal');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:GradeAppeal');
    }

    public function replicate(AuthUser $authUser, GradeAppeal $gradeAppeal): bool
    {
        return $authUser->can('Replicate:GradeAppeal');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:GradeAppeal');
    }

}
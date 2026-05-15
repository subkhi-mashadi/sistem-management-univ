<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\GradeSchema;
use Illuminate\Auth\Access\HandlesAuthorization;

class GradeSchemaPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:GradeSchema');
    }

    public function view(AuthUser $authUser, GradeSchema $gradeSchema): bool
    {
        return $authUser->can('View:GradeSchema');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:GradeSchema');
    }

    public function update(AuthUser $authUser, GradeSchema $gradeSchema): bool
    {
        return $authUser->can('Update:GradeSchema');
    }

    public function delete(AuthUser $authUser, GradeSchema $gradeSchema): bool
    {
        return $authUser->can('Delete:GradeSchema');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:GradeSchema');
    }

    public function restore(AuthUser $authUser, GradeSchema $gradeSchema): bool
    {
        return $authUser->can('Restore:GradeSchema');
    }

    public function forceDelete(AuthUser $authUser, GradeSchema $gradeSchema): bool
    {
        return $authUser->can('ForceDelete:GradeSchema');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:GradeSchema');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:GradeSchema');
    }

    public function replicate(AuthUser $authUser, GradeSchema $gradeSchema): bool
    {
        return $authUser->can('Replicate:GradeSchema');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:GradeSchema');
    }

}
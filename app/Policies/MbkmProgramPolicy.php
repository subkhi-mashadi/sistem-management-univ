<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\MbkmProgram;
use Illuminate\Auth\Access\HandlesAuthorization;

class MbkmProgramPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:MbkmProgram');
    }

    public function view(AuthUser $authUser, MbkmProgram $mbkmProgram): bool
    {
        return $authUser->can('View:MbkmProgram');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:MbkmProgram');
    }

    public function update(AuthUser $authUser, MbkmProgram $mbkmProgram): bool
    {
        return $authUser->can('Update:MbkmProgram');
    }

    public function delete(AuthUser $authUser, MbkmProgram $mbkmProgram): bool
    {
        return $authUser->can('Delete:MbkmProgram');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:MbkmProgram');
    }

    public function restore(AuthUser $authUser, MbkmProgram $mbkmProgram): bool
    {
        return $authUser->can('Restore:MbkmProgram');
    }

    public function forceDelete(AuthUser $authUser, MbkmProgram $mbkmProgram): bool
    {
        return $authUser->can('ForceDelete:MbkmProgram');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:MbkmProgram');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:MbkmProgram');
    }

    public function replicate(AuthUser $authUser, MbkmProgram $mbkmProgram): bool
    {
        return $authUser->can('Replicate:MbkmProgram');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:MbkmProgram');
    }

}
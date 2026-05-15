<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ThesisDefense;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThesisDefensePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ThesisDefense');
    }

    public function view(AuthUser $authUser, ThesisDefense $thesisDefense): bool
    {
        return $authUser->can('View:ThesisDefense');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ThesisDefense');
    }

    public function update(AuthUser $authUser, ThesisDefense $thesisDefense): bool
    {
        return $authUser->can('Update:ThesisDefense');
    }

    public function delete(AuthUser $authUser, ThesisDefense $thesisDefense): bool
    {
        return $authUser->can('Delete:ThesisDefense');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ThesisDefense');
    }

    public function restore(AuthUser $authUser, ThesisDefense $thesisDefense): bool
    {
        return $authUser->can('Restore:ThesisDefense');
    }

    public function forceDelete(AuthUser $authUser, ThesisDefense $thesisDefense): bool
    {
        return $authUser->can('ForceDelete:ThesisDefense');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ThesisDefense');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ThesisDefense');
    }

    public function replicate(AuthUser $authUser, ThesisDefense $thesisDefense): bool
    {
        return $authUser->can('Replicate:ThesisDefense');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ThesisDefense');
    }

}
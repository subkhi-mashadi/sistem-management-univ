<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ThesisAdvisor;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThesisAdvisorPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ThesisAdvisor');
    }

    public function view(AuthUser $authUser, ThesisAdvisor $thesisAdvisor): bool
    {
        return $authUser->can('View:ThesisAdvisor');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ThesisAdvisor');
    }

    public function update(AuthUser $authUser, ThesisAdvisor $thesisAdvisor): bool
    {
        return $authUser->can('Update:ThesisAdvisor');
    }

    public function delete(AuthUser $authUser, ThesisAdvisor $thesisAdvisor): bool
    {
        return $authUser->can('Delete:ThesisAdvisor');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ThesisAdvisor');
    }

    public function restore(AuthUser $authUser, ThesisAdvisor $thesisAdvisor): bool
    {
        return $authUser->can('Restore:ThesisAdvisor');
    }

    public function forceDelete(AuthUser $authUser, ThesisAdvisor $thesisAdvisor): bool
    {
        return $authUser->can('ForceDelete:ThesisAdvisor');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ThesisAdvisor');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ThesisAdvisor');
    }

    public function replicate(AuthUser $authUser, ThesisAdvisor $thesisAdvisor): bool
    {
        return $authUser->can('Replicate:ThesisAdvisor');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ThesisAdvisor');
    }

}
<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ResearchRepository;
use Illuminate\Auth\Access\HandlesAuthorization;

class ResearchRepositoryPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ResearchRepository');
    }

    public function view(AuthUser $authUser, ResearchRepository $researchRepository): bool
    {
        return $authUser->can('View:ResearchRepository');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ResearchRepository');
    }

    public function update(AuthUser $authUser, ResearchRepository $researchRepository): bool
    {
        return $authUser->can('Update:ResearchRepository');
    }

    public function delete(AuthUser $authUser, ResearchRepository $researchRepository): bool
    {
        return $authUser->can('Delete:ResearchRepository');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ResearchRepository');
    }

    public function restore(AuthUser $authUser, ResearchRepository $researchRepository): bool
    {
        return $authUser->can('Restore:ResearchRepository');
    }

    public function forceDelete(AuthUser $authUser, ResearchRepository $researchRepository): bool
    {
        return $authUser->can('ForceDelete:ResearchRepository');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ResearchRepository');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ResearchRepository');
    }

    public function replicate(AuthUser $authUser, ResearchRepository $researchRepository): bool
    {
        return $authUser->can('Replicate:ResearchRepository');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ResearchRepository');
    }

}
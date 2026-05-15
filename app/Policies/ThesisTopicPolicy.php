<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ThesisTopic;
use Illuminate\Auth\Access\HandlesAuthorization;

class ThesisTopicPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ThesisTopic');
    }

    public function view(AuthUser $authUser, ThesisTopic $thesisTopic): bool
    {
        return $authUser->can('View:ThesisTopic');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ThesisTopic');
    }

    public function update(AuthUser $authUser, ThesisTopic $thesisTopic): bool
    {
        return $authUser->can('Update:ThesisTopic');
    }

    public function delete(AuthUser $authUser, ThesisTopic $thesisTopic): bool
    {
        return $authUser->can('Delete:ThesisTopic');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ThesisTopic');
    }

    public function restore(AuthUser $authUser, ThesisTopic $thesisTopic): bool
    {
        return $authUser->can('Restore:ThesisTopic');
    }

    public function forceDelete(AuthUser $authUser, ThesisTopic $thesisTopic): bool
    {
        return $authUser->can('ForceDelete:ThesisTopic');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ThesisTopic');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ThesisTopic');
    }

    public function replicate(AuthUser $authUser, ThesisTopic $thesisTopic): bool
    {
        return $authUser->can('Replicate:ThesisTopic');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ThesisTopic');
    }

}
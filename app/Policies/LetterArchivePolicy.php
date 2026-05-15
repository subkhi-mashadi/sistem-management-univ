<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\LetterArchive;
use Illuminate\Auth\Access\HandlesAuthorization;

class LetterArchivePolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:LetterArchive');
    }

    public function view(AuthUser $authUser, LetterArchive $letterArchive): bool
    {
        return $authUser->can('View:LetterArchive');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:LetterArchive');
    }

    public function update(AuthUser $authUser, LetterArchive $letterArchive): bool
    {
        return $authUser->can('Update:LetterArchive');
    }

    public function delete(AuthUser $authUser, LetterArchive $letterArchive): bool
    {
        return $authUser->can('Delete:LetterArchive');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:LetterArchive');
    }

    public function restore(AuthUser $authUser, LetterArchive $letterArchive): bool
    {
        return $authUser->can('Restore:LetterArchive');
    }

    public function forceDelete(AuthUser $authUser, LetterArchive $letterArchive): bool
    {
        return $authUser->can('ForceDelete:LetterArchive');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:LetterArchive');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:LetterArchive');
    }

    public function replicate(AuthUser $authUser, LetterArchive $letterArchive): bool
    {
        return $authUser->can('Replicate:LetterArchive');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:LetterArchive');
    }

}
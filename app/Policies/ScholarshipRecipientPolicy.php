<?php

declare(strict_types=1);

namespace App\Policies;

use Illuminate\Foundation\Auth\User as AuthUser;
use App\Models\ScholarshipRecipient;
use Illuminate\Auth\Access\HandlesAuthorization;

class ScholarshipRecipientPolicy
{
    use HandlesAuthorization;
    
    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:ScholarshipRecipient');
    }

    public function view(AuthUser $authUser, ScholarshipRecipient $scholarshipRecipient): bool
    {
        return $authUser->can('View:ScholarshipRecipient');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:ScholarshipRecipient');
    }

    public function update(AuthUser $authUser, ScholarshipRecipient $scholarshipRecipient): bool
    {
        return $authUser->can('Update:ScholarshipRecipient');
    }

    public function delete(AuthUser $authUser, ScholarshipRecipient $scholarshipRecipient): bool
    {
        return $authUser->can('Delete:ScholarshipRecipient');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:ScholarshipRecipient');
    }

    public function restore(AuthUser $authUser, ScholarshipRecipient $scholarshipRecipient): bool
    {
        return $authUser->can('Restore:ScholarshipRecipient');
    }

    public function forceDelete(AuthUser $authUser, ScholarshipRecipient $scholarshipRecipient): bool
    {
        return $authUser->can('ForceDelete:ScholarshipRecipient');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:ScholarshipRecipient');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:ScholarshipRecipient');
    }

    public function replicate(AuthUser $authUser, ScholarshipRecipient $scholarshipRecipient): bool
    {
        return $authUser->can('Replicate:ScholarshipRecipient');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:ScholarshipRecipient');
    }

}
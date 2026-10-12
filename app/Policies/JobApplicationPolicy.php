<?php

namespace App\Policies;

use App\Models\JobApplication;
use App\Models\User;

/**
 * Application permissions (spec §26.8, §27): candidate data is only open to
 * the Careers roles, export is granted separately, and permanent deletion of
 * a candidate's data is reserved for Super Admins.
 */
class JobApplicationPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasCareersAccess();
    }

    public function view(User $user, JobApplication $application): bool
    {
        return $user->hasCareersAccess();
    }

    /**
     * Change status, assign/reassign, add notes and archive.
     */
    public function update(User $user, JobApplication $application): bool
    {
        return $user->hasCareersAccess();
    }

    /**
     * Permanently erase an archived application and its CV (retention).
     */
    public function delete(User $user, JobApplication $application): bool
    {
        return $user->isSuperAdmin() && $application->isArchived();
    }

    public function export(User $user): bool
    {
        return $user->canExportApplications();
    }
}

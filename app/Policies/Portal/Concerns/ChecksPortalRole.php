<?php

namespace App\Policies\Portal\Concerns;

use App\Enums\Portal\PortalRole;
use App\Models\Portal\Employee;
use App\Models\User;

/**
 * Shared role questions every portal policy asks, so the four capability tiers
 * are defined once instead of being re-derived in each policy.
 */
trait ChecksPortalRole
{
    protected function role(User $user): ?PortalRole
    {
        return $user->portalRole();
    }

    /**
     * Any portal role can read; users without one never reach a policy because
     * the `portal` middleware rejects them first.
     */
    protected function canRead(User $user): bool
    {
        return $user->hasPortalAccess();
    }

    /**
     * Viewers are read-only across the entire portal.
     */
    protected function canWrite(User $user): bool
    {
        return $this->role($user)?->canWrite() ?? false;
    }

    protected function manages(User $user): bool
    {
        return $this->role($user)?->managesOthers() ?? false;
    }

    protected function isDirector(User $user): bool
    {
        return $this->role($user)?->isDirector() ?? false;
    }

    /**
     * Whether the employee record belongs to this login.
     */
    protected function owns(User $user, ?Employee $employee): bool
    {
        return $employee !== null && $employee->user_id === $user->id;
    }

    /**
     * A manager's span of control: their own team plus themselves. Directors
     * supervise everyone.
     */
    protected function supervises(User $user, ?Employee $employee): bool
    {
        if ($employee === null) {
            return false;
        }

        if ($this->isDirector($user)) {
            return true;
        }

        if (! $this->manages($user)) {
            return false;
        }

        $manager = $user->employee;

        if ($manager === null) {
            return false;
        }

        return $employee->id === $manager->id
            || $employee->reports_to_id === $manager->id
            || ($manager->department_id !== null && $employee->department_id === $manager->department_id);
    }
}

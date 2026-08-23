<?php

namespace App\Policies\Portal;

use App\Models\Portal\Employee;
use App\Models\User;
use App\Policies\Portal\Concerns\ChecksPortalRole;

class EmployeePolicy
{
    use ChecksPortalRole;

    /**
     * The staff directory is readable by everyone in the portal — you cannot
     * assign or chase work without knowing who your colleagues are.
     */
    public function viewAny(User $user): bool
    {
        return $this->canRead($user);
    }

    public function view(User $user, Employee $employee): bool
    {
        return $this->canRead($user);
    }

    public function create(User $user): bool
    {
        return $this->canWrite($user) && $this->manages($user);
    }

    /**
     * Employees maintain their own contact details; the rest is management's.
     */
    public function update(User $user, Employee $employee): bool
    {
        if (! $this->canWrite($user)) {
            return false;
        }

        return $this->manages($user) || $this->owns($user, $employee);
    }

    /**
     * Removing a person from the org chart is director-only; managers
     * deactivate instead.
     */
    public function delete(User $user, Employee $employee): bool
    {
        return $this->isDirector($user);
    }

    /**
     * Granting portal access and changing someone's role is director-only.
     */
    public function manageAccess(User $user): bool
    {
        return $this->isDirector($user);
    }
}

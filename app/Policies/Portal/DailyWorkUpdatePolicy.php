<?php

namespace App\Policies\Portal;

use App\Models\Portal\DailyWorkUpdate;
use App\Models\User;
use App\Policies\Portal\Concerns\ChecksPortalRole;

class DailyWorkUpdatePolicy
{
    use ChecksPortalRole;

    public function viewAny(User $user): bool
    {
        return $this->canRead($user);
    }

    /**
     * An employee's day is their own record; managers see their team's and
     * directors see the whole company.
     */
    public function view(User $user, DailyWorkUpdate $update): bool
    {
        return $this->owns($user, $update->employee) || $this->supervises($user, $update->employee);
    }

    public function create(User $user): bool
    {
        return $this->canWrite($user);
    }

    /**
     * Employees correct their own entries; managers correct their team's.
     */
    public function update(User $user, DailyWorkUpdate $update): bool
    {
        if (! $this->canWrite($user)) {
            return false;
        }

        return $this->owns($user, $update->employee) || $this->supervises($user, $update->employee);
    }

    /**
     * Deletion removes evidence of what was worked on, so it stays with
     * management — employees correct entries by editing them.
     */
    public function delete(User $user, DailyWorkUpdate $update): bool
    {
        return $this->manages($user) && $this->supervises($user, $update->employee);
    }
}

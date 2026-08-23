<?php

namespace App\Policies\Portal;

use App\Models\Portal\Department;
use App\Models\User;
use App\Policies\Portal\Concerns\ChecksPortalRole;

class DepartmentPolicy
{
    use ChecksPortalRole;

    public function viewAny(User $user): bool
    {
        return $this->canRead($user);
    }

    public function view(User $user, Department $department): bool
    {
        return $this->canRead($user);
    }

    public function create(User $user): bool
    {
        return $this->canWrite($user) && $this->manages($user);
    }

    public function update(User $user, Department $department): bool
    {
        return $this->canWrite($user) && $this->manages($user);
    }

    /**
     * Reshaping the org structure is director-only.
     */
    public function delete(User $user, Department $department): bool
    {
        return $this->isDirector($user);
    }
}

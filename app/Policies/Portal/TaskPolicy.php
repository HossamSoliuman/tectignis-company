<?php

namespace App\Policies\Portal;

use App\Models\Portal\Task;
use App\Models\User;
use App\Policies\Portal\Concerns\ChecksPortalRole;

class TaskPolicy
{
    use ChecksPortalRole;

    public function viewAny(User $user): bool
    {
        return $this->canRead($user);
    }

    /**
     * Employees see their own work and anything they raised; managers see their
     * team's; directors see everything.
     */
    public function view(User $user, Task $task): bool
    {
        if ($this->manages($user)) {
            return true;
        }

        return $this->involves($user, $task);
    }

    public function create(User $user): bool
    {
        return $this->canWrite($user);
    }

    public function update(User $user, Task $task): bool
    {
        if (! $this->canWrite($user)) {
            return false;
        }

        return $this->manages($user) || $this->involves($user, $task);
    }

    /**
     * Only managers and directors remove tasks — and even then it is a soft
     * delete, so the audit trail and history survive.
     */
    public function delete(User $user, Task $task): bool
    {
        return $this->manages($user);
    }

    public function restore(User $user, Task $task): bool
    {
        return $this->manages($user);
    }

    /**
     * Permanent destruction of an operational record is director-only (§35).
     */
    public function forceDelete(User $user, Task $task): bool
    {
        return $this->isDirector($user);
    }

    /**
     * Assigning work to somebody else is a management action; an employee may
     * still raise a task, but it lands on their own plate.
     */
    public function assignToOthers(User $user): bool
    {
        return $this->canWrite($user) && $this->manages($user);
    }

    /**
     * Whether the task is the user's own work — assigned to them or raised by them.
     */
    private function involves(User $user, Task $task): bool
    {
        $employee = $user->employee;

        if ($employee === null) {
            return false;
        }

        return $task->assigned_to_id === $employee->id
            || $task->created_by_id === $employee->id;
    }
}

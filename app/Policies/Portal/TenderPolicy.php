<?php

namespace App\Policies\Portal;

use App\Models\Portal\Tender;
use App\Models\User;
use App\Policies\Portal\Concerns\ChecksPortalRole;

/**
 * Who may see and change a tender.
 *
 * A tender is commercially sensitive — pricing, margins, OEM terms — so an
 * employee only reaches one they are actually working on: an owner role, or a
 * task assigned to them. Everything on the workspace (documents, OEM chasing,
 * clarifications, submission, result) authorizes through this policy, so there
 * is one place to reason about tender access.
 */
class TenderPolicy
{
    use ChecksPortalRole;

    public function viewAny(User $user): bool
    {
        return $this->canRead($user);
    }

    public function view(User $user, Tender $tender): bool
    {
        if ($this->manages($user)) {
            return true;
        }

        return $this->involves($user, $tender);
    }

    public function create(User $user): bool
    {
        return $this->canWrite($user);
    }

    public function update(User $user, Tender $tender): bool
    {
        if (! $this->canWrite($user)) {
            return false;
        }

        return $this->manages($user) || $this->involves($user, $tender);
    }

    /**
     * A tender carries the whole bid history, so removing one is director-only
     * even as a soft delete.
     */
    public function delete(User $user, Tender $tender): bool
    {
        return $this->isDirector($user);
    }

    public function restore(User $user, Tender $tender): bool
    {
        return $this->isDirector($user);
    }

    public function forceDelete(User $user, Tender $tender): bool
    {
        return $this->isDirector($user);
    }

    /**
     * Overruling the eligibility checklist is a management call (§9) — and one
     * that must always carry a recorded reason.
     */
    public function overrideEligibility(User $user, Tender $tender): bool
    {
        return $this->canWrite($user) && $this->manages($user);
    }

    /**
     * Management verification of a bid document (§10): the person who prepared
     * a document cannot be the one who signs it off.
     */
    public function verifyDocuments(User $user, Tender $tender): bool
    {
        return $this->canWrite($user) && $this->manages($user);
    }

    /**
     * Recording won/lost closes the commercial loop and feeds the win-rate
     * reports, so it stays with management.
     */
    public function recordResult(User $user, Tender $tender): bool
    {
        return $this->canWrite($user) && $this->manages($user);
    }

    /**
     * Whether the user is part of this bid — an owner role on the tender, or
     * somebody carrying one of its tasks.
     */
    private function involves(User $user, Tender $tender): bool
    {
        $employee = $user->employee;

        if ($employee === null) {
            return false;
        }

        if (in_array($employee->id, [
            $tender->assigned_employee_id,
            $tender->technical_owner_id,
            $tender->sales_owner_id,
        ], true)) {
            return true;
        }

        return $tender->tasks()
            ->where(function ($query) use ($employee): void {
                $query->where('assigned_to_id', $employee->id)
                    ->orWhere('created_by_id', $employee->id);
            })
            ->exists();
    }
}

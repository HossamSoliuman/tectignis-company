<?php

namespace App\Policies\Portal;

use App\Models\Portal\OemFollowup;
use App\Models\User;
use App\Policies\Portal\Concerns\ChecksPortalRole;

/**
 * Access to OEM chasing.
 *
 * An item attached to a tender inherits that tender's visibility — the bid is
 * the sensitive thing, not the phone call. Standalone items (an authorisation
 * chased before any tender needs it) are visible to anyone in the portal, since
 * they carry no commercial detail of their own.
 */
class OemFollowupPolicy
{
    use ChecksPortalRole;

    public function viewAny(User $user): bool
    {
        return $this->canRead($user);
    }

    public function view(User $user, OemFollowup $followup): bool
    {
        if ($followup->tender !== null) {
            return $user->can('view', $followup->tender);
        }

        return $this->canRead($user);
    }

    public function create(User $user): bool
    {
        return $this->canWrite($user);
    }

    public function update(User $user, OemFollowup $followup): bool
    {
        if (! $this->canWrite($user)) {
            return false;
        }

        if ($followup->tender !== null) {
            return $user->can('update', $followup->tender);
        }

        return true;
    }

    /**
     * Deleting a follow-up destroys its contact history, so it stays with
     * management even though anyone may create and chase one.
     */
    public function delete(User $user, OemFollowup $followup): bool
    {
        return $this->canWrite($user) && $this->manages($user);
    }
}

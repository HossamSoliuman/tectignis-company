<?php

namespace App\Policies;

use App\Models\Lead;
use App\Models\User;

/**
 * Lead permissions per spec §26.7: Super Admin has full access, Sales/Editors
 * work leads, Read-only users only view, and export is granted separately.
 */
class LeadPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canViewLeads();
    }

    public function view(User $user, Lead $lead): bool
    {
        return $user->canViewLeads();
    }

    /**
     * Change status, assign/reassign and add internal notes.
     */
    public function update(User $user, Lead $lead): bool
    {
        return $user->userRole()?->canManageLeads() ?? false;
    }

    public function delete(User $user, Lead $lead): bool
    {
        return $user->isSuperAdmin();
    }

    public function restore(User $user, Lead $lead): bool
    {
        return $user->isSuperAdmin();
    }

    public function export(User $user): bool
    {
        return $user->canExportLeads();
    }
}

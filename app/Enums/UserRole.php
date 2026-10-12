<?php

namespace App\Enums;

/**
 * Website admin access level, stored on `users.role` (spec §26.7).
 *
 * Content editors run the CMS; Sales and Read-only users only see the Leads
 * module and HR only the Careers module (spec §27). Portal-only accounts have
 * no website admin rights at all — their access lives on `users.portal_role`.
 * Lead and candidate export are separate per-user permissions
 * (`users.can_export_leads`, `users.can_export_applications`), implicit for
 * Super Admins.
 */
enum UserRole: string
{
    case SuperAdmin = 'admin';
    case Editor = 'editor';
    case Sales = 'sales';
    case ReadOnly = 'readonly';
    case Hr = 'hr';
    case PortalOnly = 'portal';

    public function label(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Super Admin',
            self::Editor => 'Content Editor',
            self::Sales => 'Sales',
            self::ReadOnly => 'Read-only',
            self::Hr => 'HR',
            self::PortalOnly => 'Portal only',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::SuperAdmin => 'Full access, including users, CAPTCHA and lead deletion.',
            self::Editor => 'Manages website content; can work leads and job applications.',
            self::Sales => 'Views, updates, assigns and annotates leads. No CMS access.',
            self::ReadOnly => 'Views leads only.',
            self::Hr => 'Careers only: job openings, designations and applications. No CMS or leads access.',
            self::PortalOnly => 'No website admin access; operations portal only.',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::SuperAdmin => 'violet',
            self::Editor => 'sky',
            self::Sales => 'emerald',
            self::ReadOnly => 'amber',
            self::Hr => 'rose',
            self::PortalOnly => 'slate',
        };
    }

    public function isSuperAdmin(): bool
    {
        return $this === self::SuperAdmin;
    }

    /**
     * Whether this role may manage website content (the CMS).
     */
    public function hasCmsAccess(): bool
    {
        return in_array($this, [self::SuperAdmin, self::Editor], true);
    }

    public function canViewLeads(): bool
    {
        return in_array($this, [self::SuperAdmin, self::Editor, self::Sales, self::ReadOnly], true);
    }

    /**
     * Change status, assign and add notes.
     */
    public function canManageLeads(): bool
    {
        return in_array($this, [self::SuperAdmin, self::Editor, self::Sales], true);
    }

    /**
     * Job openings, designations and applications: the whole Careers module.
     */
    public function hasCareersAccess(): bool
    {
        return in_array($this, [self::SuperAdmin, self::Editor, self::Hr], true);
    }

    /**
     * @return array<string, string> value => label, for select inputs.
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $role): array => [$role->value => $role->label()])
            ->all();
    }
}

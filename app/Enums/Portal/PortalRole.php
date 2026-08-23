<?php

namespace App\Enums\Portal;

/**
 * Operations portal access level, stored on `users.portal_role`.
 *
 * Deliberately separate from `users.role`, which governs the website CMS: a
 * shop-floor employee gets portal access without ever gaining CMS rights.
 */
enum PortalRole: string
{
    case Director = 'director';
    case Manager = 'manager';
    case Employee = 'employee';
    case Viewer = 'viewer';

    public function label(): string
    {
        return match ($this) {
            self::Director => 'Director',
            self::Manager => 'Manager',
            self::Employee => 'Employee',
            self::Viewer => 'Viewer',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Director => 'violet',
            self::Manager => 'sky',
            self::Employee => 'emerald',
            self::Viewer => 'slate',
        };
    }

    /**
     * Directors see and act on everything in the portal.
     */
    public function isDirector(): bool
    {
        return $this === self::Director;
    }

    /**
     * Managers supervise their team: they see all operational records but a few
     * destructive actions stay director-only.
     */
    public function managesOthers(): bool
    {
        return in_array($this, [self::Director, self::Manager], true);
    }

    /**
     * Viewers are read-only across the whole portal.
     */
    public function canWrite(): bool
    {
        return $this !== self::Viewer;
    }

    /**
     * Only directors may permanently remove operational records; everyone else
     * is limited to soft deletes at most.
     */
    public function canForceDelete(): bool
    {
        return $this === self::Director;
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

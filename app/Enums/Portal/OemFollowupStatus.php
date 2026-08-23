<?php

namespace App\Enums\Portal;

/**
 * Where an OEM request has got to (§11).
 */
enum OemFollowupStatus: string
{
    case NotRequested = 'not_requested';
    case Requested = 'requested';
    case InProcess = 'in_process';
    case Received = 'received';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::NotRequested => 'Not Requested',
            self::Requested => 'Requested',
            self::InProcess => 'In Process',
            self::Received => 'Received',
            self::Rejected => 'Rejected',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::NotRequested => 'slate',
            self::Requested => 'sky',
            self::InProcess => 'amber',
            self::Received => 'emerald',
            self::Rejected => 'rose',
        };
    }

    /**
     * Statuses that stop the chasing — nothing more is expected from the OEM.
     *
     * @return array<int, self>
     */
    public static function closed(): array
    {
        return [self::Received, self::Rejected];
    }

    public function isClosed(): bool
    {
        return in_array($this, self::closed(), true);
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status): array => [$status->value => $status->label()])
            ->all();
    }
}

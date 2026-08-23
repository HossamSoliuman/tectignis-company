<?php

namespace App\Enums\Portal;

/**
 * Where a single logged activity stood at the end of the working day.
 */
enum DailyWorkStatus: string
{
    case Completed = 'completed';
    case InProgress = 'in_progress';
    case Blocked = 'blocked';
    case Deferred = 'deferred';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Completed',
            self::InProgress => 'In Progress',
            self::Blocked => 'Blocked',
            self::Deferred => 'Deferred',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Completed => 'emerald',
            self::InProgress => 'sky',
            self::Blocked => 'rose',
            self::Deferred => 'amber',
        };
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

<?php

namespace App\Enums\Portal;

/**
 * State of a single eligibility requirement (§9).
 *
 * `NotApplicable` matters: a requirement that does not apply must not drag the
 * tender into "not eligible", but it must still be visibly answered.
 */
enum ChecklistItemStatus: string
{
    case Pending = 'pending';
    case Met = 'met';
    case NotMet = 'not_met';
    case NotApplicable = 'not_applicable';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Met => 'Met',
            self::NotMet => 'Not Met',
            self::NotApplicable => 'Not Applicable',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Met => 'emerald',
            self::NotMet => 'rose',
            self::NotApplicable => 'slate',
        };
    }

    /**
     * Whether this item still needs somebody to answer it.
     */
    public function isUnanswered(): bool
    {
        return $this === self::Pending;
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

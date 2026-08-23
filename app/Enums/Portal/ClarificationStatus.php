<?php

namespace App\Enums\Portal;

/**
 * State of a pre-bid question put to the tendering authority (§13).
 */
enum ClarificationStatus: string
{
    case Pending = 'pending';
    case Answered = 'answered';
    case Closed = 'closed';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Answered => 'Answered',
            self::Closed => 'Closed',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Answered => 'emerald',
            self::Closed => 'slate',
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

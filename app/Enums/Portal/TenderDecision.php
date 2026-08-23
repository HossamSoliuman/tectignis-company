<?php

namespace App\Enums\Portal;

/**
 * Management's bid / no-bid call on a tender (§7).
 */
enum TenderDecision: string
{
    case UnderReview = 'under_review';
    case Participate = 'participate';
    case DoNotParticipate = 'do_not_participate';

    public function label(): string
    {
        return match ($this) {
            self::UnderReview => 'Under Review',
            self::Participate => 'Participate',
            self::DoNotParticipate => 'Do Not Participate',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::UnderReview => 'amber',
            self::Participate => 'emerald',
            self::DoNotParticipate => 'rose',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $decision): array => [$decision->value => $decision->label()])
            ->all();
    }
}

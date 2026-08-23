<?php

namespace App\Enums\Portal;

/**
 * Whether we qualify to bid (§9). Derived from the eligibility checklist by
 * EligibilityEvaluator, and only ever set by hand as a recorded override.
 */
enum EligibilityStatus: string
{
    case UnderReview = 'under_review';
    case Eligible = 'eligible';
    case NotEligible = 'not_eligible';

    public function label(): string
    {
        return match ($this) {
            self::UnderReview => 'Under Review',
            self::Eligible => 'Eligible',
            self::NotEligible => 'Not Eligible',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::UnderReview => 'amber',
            self::Eligible => 'emerald',
            self::NotEligible => 'rose',
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

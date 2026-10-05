<?php

namespace App\Enums;

/**
 * Optional project start timeline on the enquiry form (spec §11.2).
 */
enum LeadTimeline: string
{
    case Immediate = 'immediate';
    case OneToThreeMonths = '1-3-months';
    case ThreeToSixMonths = '3-6-months';
    case SixPlusMonths = '6-plus-months';

    public function label(): string
    {
        return match ($this) {
            self::Immediate => 'Immediate',
            self::OneToThreeMonths => '1–3 months',
            self::ThreeToSixMonths => '3–6 months',
            self::SixPlusMonths => '6+ months',
        };
    }

    /**
     * @return array<string, string> value => label, for select inputs.
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $timeline): array => [$timeline->value => $timeline->label()])
            ->all();
    }
}

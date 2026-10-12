<?php

namespace App\Enums;

/**
 * Total experience band a candidate picks on the careers form (spec §26.10).
 */
enum CandidateExperience: string
{
    case Fresher = 'fresher';
    case OneToTwo = '1-2';
    case ThreeToFive = '3-5';
    case FiveToEight = '5-8';
    case OverEight = '8-plus';

    public function label(): string
    {
        return match ($this) {
            self::Fresher => 'Fresher',
            self::OneToTwo => '1–2 years',
            self::ThreeToFive => '3–5 years',
            self::FiveToEight => '5–8 years',
            self::OverEight => '8+ years',
        };
    }

    /**
     * @return array<string, string> value => label, for select inputs.
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $experience): array => [$experience->value => $experience->label()])
            ->all();
    }
}

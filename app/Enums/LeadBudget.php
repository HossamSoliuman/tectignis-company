<?php

namespace App\Enums;

/**
 * Optional budget range on the project enquiry form (spec §11.2).
 */
enum LeadBudget: string
{
    case Under5k = 'under-5k';
    case From5kTo15k = '5k-15k';
    case From15kTo50k = '15k-50k';
    case From50kTo100k = '50k-100k';
    case Over100k = 'over-100k';
    case NotSure = 'not-sure';

    public function label(): string
    {
        return match ($this) {
            self::Under5k => 'Under US$5,000',
            self::From5kTo15k => 'US$5,000 – 15,000',
            self::From15kTo50k => 'US$15,000 – 50,000',
            self::From50kTo100k => 'US$50,000 – 100,000',
            self::Over100k => 'Over US$100,000',
            self::NotSure => 'Not sure yet',
        };
    }

    /**
     * @return array<string, string> value => label, for select inputs.
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $budget): array => [$budget->value => $budget->label()])
            ->all();
    }
}

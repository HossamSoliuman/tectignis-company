<?php

namespace App\Enums;

/**
 * Optional budget range on the project enquiry form (spec §11.2).
 */
enum LeadBudget: string
{
    case Under1k = 'under-1k';
    case From1kTo5k = '1k-5k';
    case From5kTo10k = '5k-10k';
    case From10kTo25k = '10k-25k';
    case Over25k = 'over-25k';
    case NotSure = 'not-sure';

    public function label(): string
    {
        return match ($this) {
            self::Under1k => 'Under US$1,000',
            self::From1kTo5k => 'US$1,000 – 5,000',
            self::From5kTo10k => 'US$5,000 – 10,000',
            self::From10kTo25k => 'US$10,000 – 25,000',
            self::Over25k => 'Over US$25,000',
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

<?php

namespace App\Enums;

/**
 * Optional budget range on the project enquiry form (spec §11.2).
 */
enum LeadBudget: string
{
    case Under100 = 'under-100';
    case From100To500 = '100-500';
    case From500To1k = '500-1k';
    case From1kTo5k = '1k-5k';
    case From5kTo10k = '5k-10k';
    case Over10k = 'over-10k';
    case NotSure = 'not-sure';

    public function label(): string
    {
        return __('forms.budgets.'.$this->value);
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

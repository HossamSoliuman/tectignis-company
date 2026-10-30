<?php

namespace App\Enums;

/**
 * Delivery stage of a portfolio project. Ongoing work is labelled as such on
 * the public site so it is never mistaken for a finished delivery.
 */
enum ProjectStatus: string
{
    case Completed = 'completed';
    case Ongoing = 'ongoing';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Completed',
            self::Ongoing => 'Ongoing',
        };
    }

    /**
     * @return array<string, string> value => label, for select inputs.
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $status): array => [$status->value => $status->label()])
            ->all();
    }
}

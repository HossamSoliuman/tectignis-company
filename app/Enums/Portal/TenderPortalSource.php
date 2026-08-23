<?php

namespace App\Enums\Portal;

/**
 * Where the tender was published (§7) — GeM, the central portal, a state
 * portal, or somewhere else entirely.
 */
enum TenderPortalSource: string
{
    case Gem = 'gem';
    case Cppp = 'cppp';
    case State = 'state';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Gem => 'GeM',
            self::Cppp => 'CPPP',
            self::State => 'State Portal',
            self::Other => 'Other',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Gem => 'violet',
            self::Cppp => 'sky',
            self::State => 'indigo',
            self::Other => 'slate',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $source): array => [$source->value => $source->label()])
            ->all();
    }
}

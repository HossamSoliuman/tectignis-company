<?php

namespace App\Enums\Portal;

/**
 * How a submitted tender ended (§7) — the input to win/loss reporting.
 */
enum TenderOutcome: string
{
    case Pending = 'pending';
    case Won = 'won';
    case Lost = 'lost';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::Won => 'Won',
            self::Lost => 'Lost',
            self::Cancelled => 'Cancelled',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'amber',
            self::Won => 'emerald',
            self::Lost => 'rose',
            self::Cancelled => 'slate',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $outcome): array => [$outcome->value => $outcome->label()])
            ->all();
    }
}

<?php

namespace App\Enums\Portal;

enum TaskPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Low',
            self::Medium => 'Medium',
            self::High => 'High',
            self::Critical => 'Critical',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Low => 'slate',
            self::Medium => 'sky',
            self::High => 'amber',
            self::Critical => 'rose',
        };
    }

    /**
     * Descending urgency, used to sort "what should I work on first" lists.
     */
    public function weight(): int
    {
        return match ($this) {
            self::Critical => 4,
            self::High => 3,
            self::Medium => 2,
            self::Low => 1,
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $priority): array => [$priority->value => $priority->label()])
            ->all();
    }
}

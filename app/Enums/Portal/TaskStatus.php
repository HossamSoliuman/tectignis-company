<?php

namespace App\Enums\Portal;

enum TaskStatus: string
{
    case NotStarted = 'not_started';
    case InProgress = 'in_progress';
    case Waiting = 'waiting';
    case Completed = 'completed';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::NotStarted => 'Not Started',
            self::InProgress => 'In Progress',
            self::Waiting => 'Waiting',
            self::Completed => 'Completed',
            self::Cancelled => 'Cancelled',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::NotStarted => 'slate',
            self::InProgress => 'sky',
            self::Waiting => 'amber',
            self::Completed => 'emerald',
            self::Cancelled => 'rose',
        };
    }

    /**
     * Statuses that take a task out of the overdue engine — a completed or
     * cancelled task can never be late.
     *
     * @return array<int, self>
     */
    public static function closed(): array
    {
        return [self::Completed, self::Cancelled];
    }

    public function isClosed(): bool
    {
        return in_array($this, self::closed(), true);
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

<?php

namespace App\Enums\Portal;

/**
 * Preparation state of a bid document (§10).
 */
enum TenderDocumentStatus: string
{
    case Pending = 'pending';
    case InProgress = 'in_progress';
    case Ready = 'ready';
    case Uploaded = 'uploaded';
    case Rejected = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'Pending',
            self::InProgress => 'In Progress',
            self::Ready => 'Ready',
            self::Uploaded => 'Uploaded',
            self::Rejected => 'Rejected',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Pending => 'slate',
            self::InProgress => 'sky',
            self::Ready => 'emerald',
            self::Uploaded => 'violet',
            self::Rejected => 'rose',
        };
    }

    /**
     * Statuses that count a document as done — the gate the final submission
     * checklist and the completion percentage both read.
     *
     * @return array<int, self>
     */
    public static function settled(): array
    {
        return [self::Ready, self::Uploaded];
    }

    public function isSettled(): bool
    {
        return in_array($this, self::settled(), true);
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

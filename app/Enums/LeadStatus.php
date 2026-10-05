<?php

namespace App\Enums;

/**
 * Sales pipeline stage of a website lead (spec §26.5).
 */
enum LeadStatus: string
{
    case New = 'new';
    case Contacted = 'contacted';
    case Qualified = 'qualified';
    case Proposal = 'proposal';
    case Won = 'won';
    case Lost = 'lost';
    case Spam = 'spam';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Contacted => 'Contacted',
            self::Qualified => 'Qualified',
            self::Proposal => 'Proposal',
            self::Won => 'Won',
            self::Lost => 'Lost',
            self::Spam => 'Spam',
        };
    }

    /**
     * Tailwind classes for the status pill.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::New => 'bg-fuchsia-100 text-fuchsia-700',
            self::Contacted => 'bg-sky-100 text-sky-700',
            self::Qualified => 'bg-indigo-100 text-indigo-700',
            self::Proposal => 'bg-amber-100 text-amber-700',
            self::Won => 'bg-emerald-100 text-emerald-700',
            self::Lost => 'bg-slate-200 text-slate-600',
            self::Spam => 'bg-rose-100 text-rose-700',
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

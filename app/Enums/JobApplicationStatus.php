<?php

namespace App\Enums;

/**
 * Recruitment pipeline stage of a job application (spec §26.8).
 */
enum JobApplicationStatus: string
{
    case New = 'new';
    case Screening = 'screening';
    case Shortlisted = 'shortlisted';
    case Interview = 'interview';
    case Selected = 'selected';
    case Rejected = 'rejected';
    case OnHold = 'on-hold';

    public function label(): string
    {
        return match ($this) {
            self::New => 'New',
            self::Screening => 'Screening',
            self::Shortlisted => 'Shortlisted',
            self::Interview => 'Interview',
            self::Selected => 'Selected',
            self::Rejected => 'Rejected',
            self::OnHold => 'On Hold',
        };
    }

    /**
     * Tailwind classes for the status pill.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::New => 'bg-fuchsia-100 text-fuchsia-700',
            self::Screening => 'bg-sky-100 text-sky-700',
            self::Shortlisted => 'bg-indigo-100 text-indigo-700',
            self::Interview => 'bg-amber-100 text-amber-700',
            self::Selected => 'bg-emerald-100 text-emerald-700',
            self::Rejected => 'bg-rose-100 text-rose-700',
            self::OnHold => 'bg-slate-200 text-slate-600',
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

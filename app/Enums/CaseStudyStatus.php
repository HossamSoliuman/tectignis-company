<?php

namespace App\Enums;

/**
 * Publication state of a portfolio project. Only published projects appear
 * on the public site; drafts can be previewed by admins, archived projects
 * are kept but no longer shown.
 */
enum CaseStudyStatus: string
{
    case Draft = 'draft';
    case Published = 'published';
    case Archived = 'archived';

    public function label(): string
    {
        return match ($this) {
            self::Draft => 'Draft',
            self::Published => 'Published',
            self::Archived => 'Archived',
        };
    }

    /**
     * Tailwind classes for the status pill.
     */
    public function badgeClasses(): string
    {
        return match ($this) {
            self::Draft => 'bg-slate-100 text-slate-600',
            self::Published => 'bg-emerald-100 text-emerald-700',
            self::Archived => 'bg-amber-100 text-amber-700',
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

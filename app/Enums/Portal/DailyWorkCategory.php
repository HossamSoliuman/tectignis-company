<?php

namespace App\Enums\Portal;

enum DailyWorkCategory: string
{
    case Task = 'task';
    case Tender = 'tender';
    case Sales = 'sales';
    case Customer = 'customer';
    case Technical = 'technical';
    case Admin = 'admin';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Task => 'Task',
            self::Tender => 'Tender',
            self::Sales => 'Sales',
            self::Customer => 'Customer',
            self::Technical => 'Technical',
            self::Admin => 'Admin',
            self::Other => 'Other',
        };
    }

    public function badgeColor(): string
    {
        return match ($this) {
            self::Task => 'sky',
            self::Tender => 'violet',
            self::Sales => 'emerald',
            self::Customer => 'indigo',
            self::Technical => 'amber',
            self::Admin => 'slate',
            self::Other => 'slate',
        };
    }

    /**
     * @return array<string, string>
     */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $category): array => [$category->value => $category->label()])
            ->all();
    }
}

<?php

namespace App\Enums;

/**
 * Groups used to present the technology stack on the homepage (spec §4.3).
 */
enum TechStackCategory: string
{
    case Frontend = 'frontend';
    case Backend = 'backend';
    case Mobile = 'mobile';
    case Database = 'database';
    case CloudDevops = 'cloud_devops';
    case AiMl = 'ai_ml';
    case Ecommerce = 'ecommerce';
    case Infrastructure = 'infrastructure';

    public function label(): string
    {
        return match ($this) {
            self::Frontend => 'Frontend',
            self::Backend => 'Backend',
            self::Mobile => 'Mobile',
            self::Database => 'Database',
            self::CloudDevops => 'Cloud & DevOps',
            self::AiMl => 'AI & ML',
            self::Ecommerce => 'CMS & E-commerce',
            self::Infrastructure => 'Infrastructure',
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

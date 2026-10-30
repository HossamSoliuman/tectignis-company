<?php

namespace App\Support;

/**
 * The service categories the public portfolio is filtered by. Admins can add
 * more under Case Study Categories; these are the defaults every install has.
 */
final class PortfolioCategories
{
    /**
     * @var list<string>
     */
    public const NAMES = [
        'Software',
        'SaaS',
        'AI / Automation',
        'Website',
        'E-commerce',
        'Mobile App',
        'Cloud',
        'Cybersecurity',
        'Networking',
        'CCTV',
        'Infrastructure',
        'Other',
    ];

    /**
     * The default category for an older, free-text category name, matched on
     * keywords so marketing phrases map onto a service category.
     */
    public static function match(string $name): string
    {
        $name = strtolower($name);

        return match (true) {
            str_contains($name, 'commerce') || str_contains($name, 'shop') || str_contains($name, 'store') => 'E-commerce',
            str_contains($name, 'saas') => 'SaaS',
            preg_match('/\bai\b/', $name) === 1 || str_contains($name, 'automation') => 'AI / Automation',
            str_contains($name, 'mobile') || str_contains($name, 'app ') => 'Mobile App',
            str_contains($name, 'cloud') => 'Cloud',
            str_contains($name, 'security') || str_contains($name, 'cyber') => 'Cybersecurity',
            str_contains($name, 'network') => 'Networking',
            str_contains($name, 'cctv') || str_contains($name, 'surveillance') => 'CCTV',
            str_contains($name, 'infra') => 'Infrastructure',
            str_contains($name, 'software') || str_contains($name, 'erp') || str_contains($name, 'crm') => 'Software',
            str_contains($name, 'web') || str_contains($name, 'digital') => 'Website',
            default => 'Other',
        };
    }
}

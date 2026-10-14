<?php

namespace App\Support;

use App\Enums\Pillar;

/**
 * The approved "Service Required" options on the project enquiry form
 * (spec §11.2, §26.5), grouped under the four business pillars.
 */
final class EnquiryServices
{
    /**
     * Options that do not belong to a single pillar.
     *
     * @var list<string>
     */
    private const GENERAL = ['IT Consulting', 'Other / Not sure'];

    /**
     * Enquiry-only options added to a pillar's primary services, e.g. the
     * Website Development priority service that the landing pages preset.
     *
     * @var array<string, list<string>> pillar value => service names
     */
    private const EXTRA = [
        'software-development' => ['Website Development'],
    ];

    /**
     * @return array<string, list<string>> group label => service names
     */
    public static function grouped(): array
    {
        $groups = [];

        foreach (Pillar::cases() as $pillar) {
            $groups[$pillar->label()] = [...$pillar->primaryServices(), ...(self::EXTRA[$pillar->value] ?? [])];
        }

        $groups['General'] = self::GENERAL;

        return $groups;
    }

    /**
     * Every accepted service name.
     *
     * @return list<string>
     */
    public static function all(): array
    {
        return array_values(array_unique(array_merge(...array_values(self::grouped()))));
    }
}

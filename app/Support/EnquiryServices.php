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
     * @return array<string, list<string>> group label => service names
     */
    public static function grouped(): array
    {
        $groups = [];

        foreach (Pillar::cases() as $pillar) {
            $groups[$pillar->label()] = $pillar->primaryServices();
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

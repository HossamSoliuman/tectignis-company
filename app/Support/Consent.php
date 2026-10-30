<?php

namespace App\Support;

use Illuminate\Http\Request;

/**
 * Cookie consent for analytics and advertising tags (Google Consent Mode v2
 * and the Meta Pixel). Visitors in the EEA, the UK and Switzerland start with
 * tracking denied and are asked; everyone else starts granted and can change
 * their choice from the footer.
 */
final class Consent
{
    /**
     * First-party cookie holding the visitor's choice: "granted" or "denied".
     */
    public const COOKIE = 'tx_consent';

    /**
     * Days the choice is remembered.
     */
    public const LIFETIME_DAYS = 180;

    /**
     * EEA member states, the UK and Switzerland, as ISO 3166-1 alpha-2 codes.
     *
     * @var list<string>
     */
    public const REGIONS = [
        'AT', 'BE', 'BG', 'HR', 'CY', 'CZ', 'DK', 'EE', 'FI', 'FR', 'DE', 'GR', 'HU', 'IE', 'IT',
        'LV', 'LT', 'LU', 'MT', 'NL', 'PL', 'PT', 'RO', 'SK', 'SI', 'ES', 'SE', 'IS', 'LI', 'NO',
        'GB', 'CH',
    ];

    /**
     * Whether the visitor must opt in before tracking runs.
     */
    public static function requiredFor(Request $request): bool
    {
        return in_array(Countries::detect($request), self::REGIONS, true);
    }
}

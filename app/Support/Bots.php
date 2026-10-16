<?php

namespace App\Support;

use Illuminate\Support\Str;

/**
 * Recognises the most common crawlers, so they are left out of the visit
 * chart and never redirected by language detection.
 */
final class Bots
{
    public static function is(?string $userAgent): bool
    {
        if ($userAgent === null || $userAgent === '') {
            return true;
        }

        return Str::contains(Str::lower($userAgent), [
            'bot', 'crawl', 'spider', 'slurp', 'bingpreview', 'facebookexternalhit', 'embedly', 'pingdom',
        ]);
    }
}

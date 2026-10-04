<?php

namespace App\Models\Concerns;

use Illuminate\Support\Facades\Cache;

/**
 * Forgets the long-lived public-site caches whenever a record that feeds them is
 * created, updated or deleted. This keeps admin edits instant while letting every
 * other request serve the cached settings map, header navigation, footer service
 * list, home page payload and redirect map without touching the database.
 */
trait FlushesSiteCache
{
    /**
     * Cache keys built from admin-managed content. Declared here so the read sites
     * (Setting::values(), the header composer, the footer partial, HomeController
     * and HandleRedirects) and this invalidator never drift apart.
     *
     * @var list<string>
     */
    public const SITE_CACHE_KEYS = [
        'site.settings',
        'site.nav',
        'site.footer_services',
        'site.home',
        'site.redirects',
        'site.stats',
        'site.footer',
    ];

    protected static function bootFlushesSiteCache(): void
    {
        static::saved(static fn () => static::flushSiteCache());
        static::deleted(static fn () => static::flushSiteCache());
    }

    public static function flushSiteCache(): void
    {
        foreach (self::SITE_CACHE_KEYS as $key) {
            Cache::forget($key);
        }
    }
}

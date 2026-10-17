<?php

namespace App\Support;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * The languages the public site is served in and the URLs between them.
 * config/locales.php holds the fixed facts per language; which languages are
 * live, which service pages are translated and language detection are
 * settings a Super Admin changes in the Languages tab. English keeps today's
 * unprefixed URLs; every other language serves the translated pages under
 * its own prefix, under the same route names with a `localized.` prefix.
 */
final class Locales
{
    /**
     * Remembers the language of the last translated page the visitor saw, so
     * first-visit detection runs once and never overrides a manual choice.
     */
    public const COOKIE = 'locale';

    public const ROUTE_PREFIX = 'localized.';

    /**
     * The settings group behind the Languages tab, kept off the Site Settings
     * screen because only a Super Admin may change it.
     */
    public const SETTINGS_GROUP = 'locales';

    /**
     * Setting keys: the live languages, who last switched each one on or off
     * and when, the translated service slugs, and first-visit detection. The
     * detection key predates country detection and is kept for stored settings.
     */
    public const SETTING_ENABLED = 'locales_enabled';

    public const SETTING_CHANGES = 'locales_changes';

    public const SETTING_TRANSLATED_SERVICES = 'locales_translated_services';

    public const SETTING_DETECT_LANGUAGE = 'locales_detect_browser';

    public static function default(): string
    {
        return config('locales.default');
    }

    public static function current(): string
    {
        return app()->getLocale();
    }

    public static function isDefault(?string $code = null): bool
    {
        return ($code ?? self::current()) === self::default();
    }

    /**
     * @return array<string, array{label: string, name: string, dir: string, og_locale: string}>
     */
    public static function supported(): array
    {
        return config('locales.supported');
    }

    /**
     * Every supported code, English first.
     *
     * @return list<string>
     */
    public static function codes(): array
    {
        return array_keys(self::supported());
    }

    /**
     * Every language served under a prefix, enabled or not.
     *
     * @return list<string>
     */
    public static function prefixed(): array
    {
        return array_values(array_diff(self::codes(), [self::default()]));
    }

    /**
     * Languages visitors can reach, English first. English is always on.
     *
     * @return list<string>
     */
    public static function enabled(): array
    {
        $switchedOn = Setting::json(self::SETTING_ENABLED);

        return array_values(array_filter(
            self::codes(),
            fn (string $code): bool => self::isDefault($code) || in_array($code, $switchedOn, true),
        ));
    }

    public static function isSupported(string $code): bool
    {
        return array_key_exists($code, self::supported());
    }

    public static function isEnabled(?string $code = null): bool
    {
        return in_array($code ?? self::current(), self::enabled(), true);
    }

    /**
     * Whether the language's fixed text exists, which switching it on requires.
     */
    public static function hasLangFiles(string $code): bool
    {
        return is_dir(lang_path($code));
    }

    /**
     * Switch a language on or off for visitors, remembering who did it and when.
     */
    public static function switchLanguage(string $code, bool $enabled, User $user): void
    {
        $switchedOn = array_diff(Setting::json(self::SETTING_ENABLED), [$code]);

        if ($enabled) {
            $switchedOn[] = $code;
        }

        $changes = self::changes();
        $changes[$code] = ['enabled' => $enabled, 'by' => $user->id, 'at' => now()->toIso8601String()];

        Setting::set(self::SETTING_ENABLED, json_encode(array_values(array_intersect(self::prefixed(), $switchedOn))), self::SETTINGS_GROUP);
        Setting::set(self::SETTING_CHANGES, json_encode($changes), self::SETTINGS_GROUP);
    }

    /**
     * The last time each language was switched on or off.
     *
     * @return array<string, array{enabled: bool, by: int, at: string}>
     */
    public static function changes(): array
    {
        return Setting::json(self::SETTING_CHANGES);
    }

    /**
     * Slugs of the service pages that have a version in every language.
     *
     * @return list<string>
     */
    public static function translatedServices(): array
    {
        return array_values(array_filter(Setting::json(self::SETTING_TRANSLATED_SERVICES), 'is_string'));
    }

    /**
     * @param  list<string>  $slugs
     */
    public static function saveTranslatedServices(array $slugs): void
    {
        Setting::set(self::SETTING_TRANSLATED_SERVICES, json_encode(array_values(array_unique($slugs))), self::SETTINGS_GROUP);
    }

    /**
     * Whether a first visit to a translated English page is sent to the live
     * language of the visitor's browser or country. On unless switched off.
     */
    public static function detectsVisitorLanguage(): bool
    {
        return Setting::get(self::SETTING_DETECT_LANGUAGE, '1') !== '0';
    }

    public static function saveVisitorLanguageDetection(bool $enabled): void
    {
        Setting::set(self::SETTING_DETECT_LANGUAGE, $enabled ? '1' : '0', self::SETTINGS_GROUP);
    }

    /**
     * Enabled languages are public; a disabled one is shown only to CMS users
     * checking it before it is switched on.
     */
    public static function isViewableBy(string $code, ?User $user): bool
    {
        return self::isEnabled($code) || (self::isSupported($code) && (bool) $user?->isAdmin());
    }

    /**
     * The language's name in its own language, e.g. "العربية", for visitors.
     */
    public static function name(string $code): string
    {
        return self::supported()[$code]['name'] ?? $code;
    }

    /**
     * The language's English name, e.g. "Arabic", for the admin.
     */
    public static function label(string $code): string
    {
        return self::supported()[$code]['label'] ?? $code;
    }

    public static function direction(?string $code = null): string
    {
        return self::supported()[$code ?? self::current()]['dir'] ?? 'ltr';
    }

    public static function isRtl(?string $code = null): bool
    {
        return self::direction($code) === 'rtl';
    }

    public static function ogLocale(?string $code = null): string
    {
        return self::supported()[$code ?? self::current()]['og_locale'] ?? 'en_US';
    }

    /**
     * A cache key for content that differs per language. English keeps the
     * original key, so existing caches stay valid.
     */
    public static function cacheKey(string $key, ?string $code = null): string
    {
        $code ??= self::current();

        return self::isDefault($code) ? $key : $key.'.'.$code;
    }

    /**
     * The route name without the `localized.` prefix.
     */
    public static function baseRouteName(?string $name): ?string
    {
        return $name === null ? null : Str::after($name, self::ROUTE_PREFIX);
    }

    /**
     * Route names with a version in every language: the fixed pages from
     * config plus the service pages chosen in the Languages tab. A route
     * listed with slugs is translated for those slugs only.
     *
     * @return array<string, list<string>|null>
     */
    public static function translatedPages(): array
    {
        return [...config('locales.translated_pages'), 'services.show' => self::translatedServices()];
    }

    /**
     * Whether the page has a version in every language. Routes listed with
     * slugs are translated for those slugs only.
     */
    public static function isTranslatedPage(?string $routeName, mixed $parameters = []): bool
    {
        $pages = self::translatedPages();

        if ($routeName === null || ! array_key_exists($routeName, $pages)) {
            return false;
        }

        if ($pages[$routeName] === null) {
            return true;
        }

        $parameters = Arr::wrap($parameters);
        $slug = $parameters['slug'] ?? Arr::first($parameters);

        return is_scalar($slug) && in_array((string) $slug, $pages[$routeName], true);
    }

    /**
     * The URL of a page in the given language (the current one by default).
     * Pages without translations always link to their English URL.
     */
    public static function route(string $name, mixed $parameters = [], ?string $code = null, bool $absolute = true): string
    {
        $code ??= self::current();

        if (self::isDefault($code) || ! self::isTranslatedPage($name, $parameters)) {
            return route($name, $parameters, $absolute);
        }

        return route(self::ROUTE_PREFIX.$name, ['locale' => $code, ...Arr::wrap($parameters)], $absolute);
    }

    /**
     * The current page in another language, or that language's homepage when
     * the current page has no translations.
     */
    public static function urlFor(string $code): string
    {
        $route = request()->route();
        $name = self::baseRouteName($route?->getName());
        $parameters = $route?->parameters() ?? [];

        if (! request()->isMethod('GET') || $name === 'contact.thank-you' || ! self::isTranslatedPage($name, $parameters)) {
            return self::route('home', [], $code);
        }

        return self::route($name, $parameters, $code);
    }

    /**
     * `hreflang` alternates for the current page: every enabled language plus
     * `x-default` pointing to English. Empty on pages without translations and
     * while English is the only enabled language.
     *
     * @return array<string, string> hreflang => URL
     */
    public static function alternates(): array
    {
        $route = request()->route();
        $name = self::baseRouteName($route?->getName());
        $parameters = $route?->parameters() ?? [];
        $enabled = self::enabled();

        if (count($enabled) < 2 || $name === 'contact.thank-you' || ! self::isTranslatedPage($name, $parameters)) {
            return [];
        }

        $links = [];

        foreach ($enabled as $code) {
            $links[$code] = self::route($name, $parameters, $code);
        }

        return $links + ['x-default' => $links[self::default()]];
    }

    /**
     * The language switcher entries: the enabled languages, plus the disabled
     * ones for CMS users, who preview them before they are switched on.
     *
     * @return array<string, array{name: string, url: string, current: bool}>
     */
    public static function switcherLinks(?User $user): array
    {
        $links = [];

        foreach (self::codes() as $code) {
            if (! self::isViewableBy($code, $user)) {
                continue;
            }

            $links[$code] = [
                'name' => self::name($code),
                'url' => self::urlFor($code),
                'current' => $code === self::current(),
            ];
        }

        return count($links) > 1 ? $links : [];
    }
}

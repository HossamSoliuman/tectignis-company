<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

/**
 * The languages the public site is served in (config/locales.php) and the
 * URLs between them. English keeps today's unprefixed URLs; every other
 * language serves the translated pages under its own prefix, under the same
 * route names with a `localized.` prefix.
 */
final class Locales
{
    /**
     * Remembers the language of the last translated page the visitor saw, so
     * first-visit detection runs once and never overrides a manual choice.
     */
    public const COOKIE = 'locale';

    public const ROUTE_PREFIX = 'localized.';

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
     * @return array<string, array{label: string, name: string, dir: string, og_locale: string, enabled: bool}>
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
     * Languages visitors can reach, English first.
     *
     * @return list<string>
     */
    public static function enabled(): array
    {
        return array_keys(array_filter(self::supported(), fn (array $language): bool => $language['enabled']));
    }

    public static function isSupported(string $code): bool
    {
        return array_key_exists($code, self::supported());
    }

    public static function isEnabled(?string $code = null): bool
    {
        return self::supported()[$code ?? self::current()]['enabled'] ?? false;
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
     * Whether the page has a version in every language. Routes listed with
     * slugs are translated for those slugs only.
     */
    public static function isTranslatedPage(?string $routeName, mixed $parameters = []): bool
    {
        $pages = config('locales.translated_pages');

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

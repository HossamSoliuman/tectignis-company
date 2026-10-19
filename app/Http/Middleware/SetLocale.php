<?php

namespace App\Http\Middleware;

use App\Services\VisitorLanguageDetector;
use App\Support\Bots;
use App\Support\Locales;
use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves each request in the language of its URL: unprefixed URLs are
 * English, /ar/, /es/, ... are the other languages. A visitor arriving on a
 * translated English page for the first time is sent to the language of their
 * browser or country; after that the language cookie means they are never
 * redirected again.
 */
class SetLocale
{
    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $route = $request->route();
        $locale = $route?->parameter('locale');

        if (! $route instanceof Route || ! is_string($locale)) {
            App::setLocale(Locales::default());

            if (! Locales::isTranslatedPage($route?->getName(), $route?->parameters() ?? [])) {
                return $next($request);
            }

            return $this->detectedLanguageRedirect($request)
                ?? $this->remember($request, Locales::default(), $next);
        }

        // Controllers take route parameters positionally, so the locale must
        // not reach them.
        $route->forgetParameter('locale');

        abort_unless(Locales::isViewableBy($locale, $request->user()), 404);

        $name = Locales::baseRouteName($route->getName());

        if (! Locales::isTranslatedPage($name, $route->parameters())) {
            return $this->englishPageRedirect($request, $name, $route->parameters());
        }

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $this->remember($request, $locale, $next);
    }

    /**
     * A page without translations, such as a service page taken off the
     * translated list, moves permanently to its English URL, so indexed
     * language URLs keep working.
     *
     * @param  array<string, mixed>  $parameters
     */
    private function englishPageRedirect(Request $request, string $name, array $parameters): RedirectResponse
    {
        abort_unless($request->isMethod('GET') || $request->isMethod('HEAD'), 404);

        $query = $request->getQueryString();

        return redirect()->to(route($name, $parameters).($query ? '?'.$query : ''), 301);
    }

    /**
     * On a first visit (no language cookie), send people to the version of
     * the page in the live language of their browser, or else of their
     * country (see VisitorLanguageDetector), unless detection is switched off
     * in the Languages tab. Bots always get the URL they asked for. The query
     * string is kept, so Ads click IDs and UTM tags survive the redirect.
     * Landing pages skip the country: the ad that brought the visitor was
     * already in the page's language.
     */
    private function detectedLanguageRedirect(Request $request): ?RedirectResponse
    {
        if (! $request->isMethod('GET')
            || ! Locales::detectsVisitorLanguage()
            || $request->hasCookie(Locales::COOKIE)
            || $request->route()?->getName() === 'contact.thank-you'
            || Bots::is($request->userAgent())) {
            return null;
        }

        $language = app(VisitorLanguageDetector::class)->detect($request, useCountry: $request->route()?->getName() !== 'landing-pages.show');

        if (Locales::isDefault($language)) {
            return null;
        }

        $query = $request->getQueryString();

        return redirect()->to(Locales::urlFor($language).($query ? '?'.$query : ''));
    }

    /**
     * @param  Closure(Request): (Response)  $next
     */
    private function remember(Request $request, string $locale, Closure $next): Response
    {
        if ($request->cookie(Locales::COOKIE) !== $locale) {
            Cookie::queue(Locales::COOKIE, $locale, 60 * 24 * 365);
        }

        return $next($request);
    }
}

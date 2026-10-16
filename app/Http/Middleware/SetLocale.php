<?php

namespace App\Http\Middleware;

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
 * translated English page for the first time is sent to the language their
 * browser prefers; after that the language cookie means they are never
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
        abort_unless(Locales::isTranslatedPage(Locales::baseRouteName($route->getName()), $route->parameters()), 404);

        App::setLocale($locale);
        URL::defaults(['locale' => $locale]);

        return $this->remember($request, $locale, $next);
    }

    /**
     * On a first visit (no language cookie), send people whose browser prefers
     * an enabled language to that language's version of the page. Bots always
     * get the URL they asked for. The query string is kept, so Ads click IDs
     * and UTM tags survive the redirect.
     */
    private function detectedLanguageRedirect(Request $request): ?RedirectResponse
    {
        if (! $request->isMethod('GET')
            || $request->hasCookie(Locales::COOKIE)
            || $request->route()?->getName() === 'contact.thank-you'
            || Bots::is($request->userAgent())) {
            return null;
        }

        $preferred = $request->getPreferredLanguage(Locales::enabled());

        if ($preferred === null || Locales::isDefault($preferred) || ! Locales::isEnabled($preferred)) {
            return null;
        }

        $query = $request->getQueryString();

        return redirect()->to(Locales::urlFor($preferred).($query ? '?'.$query : ''));
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

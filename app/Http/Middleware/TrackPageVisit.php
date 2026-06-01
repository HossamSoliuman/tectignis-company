<?php

namespace App\Http\Middleware;

use App\Models\PageVisit;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

class TrackPageVisit
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($this->shouldTrack($request, $response)) {
            PageVisit::create([
                'path' => Str::limit('/'.ltrim($request->path(), '/'), 255, ''),
                'referer' => Str::limit((string) $request->headers->get('referer'), 255, '') ?: null,
            ]);
        }

        return $response;
    }

    /**
     * Only count human page views: GET, successful HTML responses on public pages.
     */
    protected function shouldTrack(Request $request, Response $response): bool
    {
        if (! $request->isMethod('GET') || $request->ajax() || $request->pjax()) {
            return false;
        }

        if ($response->getStatusCode() !== 200) {
            return false;
        }

        if (! Str::contains((string) $response->headers->get('content-type'), 'text/html')) {
            return false;
        }

        if ($request->is('admin', 'admin/*', 'login', 'sitemap.xml', 'robots.txt', 'up')) {
            return false;
        }

        return ! $this->isBot((string) $request->userAgent());
    }

    /**
     * Filter out the most common crawlers so the chart reflects real traffic.
     */
    protected function isBot(string $userAgent): bool
    {
        if ($userAgent === '') {
            return true;
        }

        return Str::contains(Str::lower($userAgent), [
            'bot', 'crawl', 'spider', 'slurp', 'bingpreview', 'facebookexternalhit', 'embedly', 'pingdom',
        ]);
    }
}

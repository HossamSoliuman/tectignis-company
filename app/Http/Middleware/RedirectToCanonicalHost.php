<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Serves the site on one hostname: a request to the www (or bare) variant of
 * the APP_URL host is sent there with a permanent redirect, keeping the path
 * and query string, so search engines and ad clicks see a single domain.
 */
class RedirectToCanonicalHost
{
    public function handle(Request $request, Closure $next): Response
    {
        $canonical = parse_url((string) config('app.url'));
        $canonicalHost = strtolower($canonical['host'] ?? '');
        $host = strtolower($request->getHost());

        if ($canonicalHost === '' || $host === $canonicalHost || ! $this->isWwwVariant($host, $canonicalHost)) {
            return $next($request);
        }

        $origin = ($canonical['scheme'] ?? $request->getScheme()).'://'.$canonicalHost
            .(isset($canonical['port']) ? ':'.$canonical['port'] : '');

        return redirect()->away($origin.$request->getRequestUri(), Response::HTTP_MOVED_PERMANENTLY);
    }

    private function isWwwVariant(string $host, string $canonicalHost): bool
    {
        return $host === 'www.'.$canonicalHost || 'www.'.$host === $canonicalHost;
    }
}

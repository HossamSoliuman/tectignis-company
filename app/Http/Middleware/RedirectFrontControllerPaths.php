<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Sends page requests made through the server's front controller path, such
 * as /public/index.php/industries or /public/industries, to the clean URL
 * with a permanent redirect, so search engines see each page once. Form
 * posts are left alone so a page already open on such a URL still submits.
 */
class RedirectFrontControllerPaths
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethodCacheable()) {
            return $next($request);
        }

        $cleanUri = preg_replace('#^/public(?:/index\.php)?(?=[/?]|$)#', '', $request->getRequestUri(), 1, $matches);

        if ($matches === 0) {
            return $next($request);
        }

        if (! str_starts_with($cleanUri, '/')) {
            $cleanUri = '/'.$cleanUri;
        }

        return redirect()->away($request->getSchemeAndHttpHost().$cleanUri, Response::HTTP_MOVED_PERMANENTLY);
    }
}

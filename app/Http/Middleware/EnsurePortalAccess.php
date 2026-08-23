<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gate for the operations portal.
 *
 * Deliberately independent of `EnsureUserIsAdmin`: portal access is granted by
 * `users.portal_role` alone, so an employee can work in the portal without ever
 * gaining website CMS rights — and a CMS editor without a portal role stays out.
 */
class EnsurePortalAccess
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user() || ! $request->user()->hasPortalAccess()) {
            abort(403);
        }

        return $next($request);
    }
}

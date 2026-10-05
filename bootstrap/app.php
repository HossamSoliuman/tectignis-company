<?php

use App\Http\Middleware\CaptureLeadAttribution;
use App\Http\Middleware\EnsurePortalAccess;
use App\Http\Middleware\EnsureUserIsAdmin;
use App\Http\Middleware\HandleRedirects;
use App\Http\Middleware\SecurityHeaders;
use App\Http\Middleware\TrackPageVisit;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => EnsureUserIsAdmin::class,
            'portal' => EnsurePortalAccess::class,
        ]);
        // HandleRedirects must run before SubstituteBindings so that paths now
        // served only by the catch-all CMS route (e.g. the removed /services
        // listing) redirect instead of 404-ing on a missing Page binding.
        $middleware->web(prepend: [
            HandleRedirects::class,
        ]);
        $middleware->web(append: [
            CaptureLeadAttribution::class,
            SecurityHeaders::class,
            TrackPageVisit::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // A form left open past the session lifetime (or submitted after the
        // session was rotated in another tab) carries a stale CSRF token.
        // Send the visitor back to the form with a message instead of the
        // bare "419 Page Expired" screen; the fresh page has a fresh token.
        $exceptions->render(function (HttpException $e, Request $request): ?RedirectResponse {
            if ($e->getStatusCode() !== 419 || $request->expectsJson()) {
                return null;
            }

            return redirect()->back()
                ->withInput($request->except(['_token', 'password', 'password_confirmation']))
                ->withErrors(['session' => 'Your session expired. Please try again.']);
        });
    })->create();

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request, adding baseline security response headers.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $headers = [
            'X-Frame-Options' => 'SAMEORIGIN',
            'X-Content-Type-Options' => 'nosniff',
            'Referrer-Policy' => 'strict-origin-when-cross-origin',
            'X-Permitted-Cross-Domain-Policies' => 'none',
            'Permissions-Policy' => 'browsing-topics=(), interest-cohort=()',
            // Report-only so it never breaks the site; surfaces violations in the
            // browser console while the inline GA/GTM/reCAPTCHA scripts are audited.
            'Content-Security-Policy-Report-Only' => $this->contentSecurityPolicy(),
        ];

        // HSTS only makes sense once the request is already served over HTTPS.
        if ($request->secure()) {
            $headers['Strict-Transport-Security'] = 'max-age=31536000; includeSubDomains';
        }

        foreach ($headers as $name => $value) {
            if (! $response->headers->has($name)) {
                $response->headers->set($name, $value);
            }
        }

        return $response;
    }

    /**
     * A permissive report-only policy covering the third-party origins already
     * used by the site (Google Analytics/Tag Manager, reCAPTCHA, Google Fonts,
     * Meta Pixel and flag CDN imagery).
     */
    private function contentSecurityPolicy(): string
    {
        $google = 'https://www.googletagmanager.com https://www.google-analytics.com https://www.google.com https://www.gstatic.com https://ssl.google-analytics.com';
        $meta = 'https://connect.facebook.net https://www.facebook.com';

        return implode('; ', [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' {$google} {$meta}",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            'font-src \'self\' https://fonts.gstatic.com data:',
            "img-src 'self' data: https:",
            "frame-src 'self' {$google}",
            "connect-src 'self' {$google} {$meta}",
            "object-src 'none'",
            "base-uri 'self'",
        ]);
    }
}

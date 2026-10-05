<?php

namespace App\Http\Middleware;

use App\Models\Lead;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Remembers the campaign a visitor arrived from (spec §11.2 "Store
 * UTM/source/medium/campaign parameters") so it can be attached to a lead
 * submitted later in the session, even several pages on.
 *
 * A landing URL carrying UTM parameters replaces whatever was stored before:
 * the most recent campaign click gets the credit.
 */
class CaptureLeadAttribution
{
    public const SESSION_KEY = 'lead_attribution';

    /**
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->isMethod('GET') && ! $request->is('admin', 'admin/*') && $request->hasSession()) {
            $utm = collect(Lead::UTM_FIELDS)
                ->mapWithKeys(fn (string $field): array => [$field => Str::limit(trim((string) $request->query($field, '')), 255, '')])
                ->filter(fn (string $value): bool => $value !== '');

            if ($utm->isNotEmpty()) {
                $request->session()->put(self::SESSION_KEY, $utm->all());
            }
        }

        return $next($request);
    }

    /**
     * The UTM values stored for the current session.
     *
     * @return array<string, string>
     */
    public static function fromSession(Request $request): array
    {
        $stored = $request->hasSession() ? $request->session()->get(self::SESSION_KEY, []) : [];

        return is_array($stored) ? array_intersect_key($stored, array_flip(Lead::UTM_FIELDS)) : [];
    }
}

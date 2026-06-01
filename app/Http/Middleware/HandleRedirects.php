<?php

namespace App\Http\Middleware;

use App\Models\Redirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandleRedirects
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = '/'.ltrim($request->path(), '/');

        try {
            $redirect = Redirect::where('from_path', $path)
                ->where('is_active', true)
                ->first();
        } catch (\Exception) {
            return $next($request);
        }

        if ($redirect) {
            $toUrl = str_starts_with($redirect->to_path, 'http')
                ? $redirect->to_path
                : url($redirect->to_path);

            return redirect($toUrl, $redirect->status_code ?: 301);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Models\SeoRedirect;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SeoRedirectMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->isMethod('GET') && ! $request->isMethod('HEAD')) {
            return $next($request);
        }

        $path = '/'.ltrim($request->getPathInfo(), '/');

        $redirect = SeoRedirect::query()
            ->where('old_url', $path)
            ->where('is_active', true)
            ->first();

        if ($redirect && in_array($redirect->status_code, [301, 302], true) && ! $this->hasCycle($request, $redirect)) {
            return redirect($redirect->new_url, $redirect->status_code);
        }

        return $next($request);
    }

    private function hasCycle(Request $request, SeoRedirect $redirect): bool
    {
        $visited = [$redirect->old_url => true];

        for ($depth = 0; $depth < 50; $depth++) {
            $target = parse_url(trim($redirect->new_url));
            if ($target === false || trim($redirect->new_url) === '') {
                return true;
            }
            if (isset($target['host']) && strcasecmp($target['host'], $request->getHost()) !== 0) {
                return false;
            }

            $path = '/'.ltrim($target['path'] ?? '/', '/');
            if (isset($visited[$path])) {
                return true;
            }
            $visited[$path] = true;

            $redirect = SeoRedirect::where('old_url', $path)->where('is_active', true)->first();
            if (! $redirect) {
                return false;
            }
        }

        return true;
    }
}

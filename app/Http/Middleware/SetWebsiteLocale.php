<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetWebsiteLocale
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $request->query('lang', 'vi');
        $admin = $request->is('admin', 'admin/*', 'livewire/*');
        app()->setLocale(! $admin && $locale === 'en' ? 'en' : 'vi');

        $response = $next($request);
        if (! $admin) {
            $response->headers->set('Content-Language', app()->getLocale());
            if ($response->isRedirect() && $response->headers->has('Location')) {
                $response->headers->set('Location', website_url($response->headers->get('Location')));
            }
        }

        return $response;
    }
}

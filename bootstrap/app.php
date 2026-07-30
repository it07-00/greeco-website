<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->append(\App\Http\Middleware\SeoRedirectMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        // Render user-friendly error pages on production (when APP_DEBUG=false).
        // This prevents scary stack traces and PHP errors from showing to non-tech users.
        $exceptions->respond(function (\Symfony\Component\HttpFoundation\Response $response, \Throwable $e, Request $request) {
            // Only customize in production (APP_DEBUG=false)
            if (config('app.debug')) {
                return $response;
            }

            // Skip API requests — they get JSON error responses
            if ($request->is('api/*') || $request->expectsJson()) {
                return $response;
            }

            $statusCode = $e instanceof HttpExceptionInterface
                ? $e->getStatusCode()
                : 500;

            // For server errors (500), log the actual exception for developers
            if ($statusCode >= 500) {
                report($e);
            }

            // Only render custom views for error codes we have templates for
            $customPages = [403, 404, 419, 500, 503];

            if (in_array($statusCode, $customPages) && view()->exists("errors.{$statusCode}")) {
                return response()->view("errors.{$statusCode}", [], $statusCode);
            }

            // For other HTTP errors, fall back to 500 page if it's a server error,
            // or 404 page for client errors we don't have a specific template for
            if ($statusCode >= 500 && view()->exists('errors.500')) {
                return response()->view('errors.500', [], $statusCode);
            }

            return $response;
        });
    })->create();


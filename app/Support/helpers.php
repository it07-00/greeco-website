<?php

use App\Models\Setting;

if (! function_exists('website_url')) {
    function website_url(string $url, ?string $locale = null): string
    {
        $parts = parse_url($url);
        if ($parts === false
            || (isset($parts['scheme']) && ! in_array($parts['scheme'], ['http', 'https'], true))
            || (isset($parts['host']) && $parts['host'] !== request()->getHost())
            || str_starts_with($url, '#')) {
            return $url;
        }

        parse_str($parts['query'] ?? '', $query);
        unset($query['lang']);
        if (($locale ?? app()->getLocale()) === 'en') {
            $query['lang'] = 'en';
        }

        return preg_replace('/[?#].*$/', '', $url)
            .($query ? '?'.http_build_query($query) : '')
            .(isset($parts['fragment']) ? '#'.$parts['fragment'] : '');
    }
}

if (! function_exists('website_route')) {
    function website_route(string $name, mixed $parameters = [], bool $absolute = true): string
    {
        return website_url(route($name, $parameters, $absolute));
    }
}

if (! function_exists('form_text')) {
    function form_text(mixed $value): string
    {
        return is_scalar($value) ? (string) $value : '';
    }
}

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return Setting::getValue($key, $default);
    }
}

if (! function_exists('versioned_asset')) {
    function versioned_asset(string $path): string
    {
        $publicPath = public_path($path);
        $version = is_file($publicPath) ? filemtime($publicPath) : null;

        return asset($path).($version ? "?v={$version}" : '');
    }
}

<?php

use App\Models\Setting;

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

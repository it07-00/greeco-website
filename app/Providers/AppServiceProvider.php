<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        require_once app_path('Support/helpers.php');

        // Robust public path detection for shared hosting / cPanel environments
        if (basename(base_path()) === 'public_html' && file_exists(base_path('index.php'))) {
            $this->app->usePublicPath(base_path());
        } elseif (file_exists(dirname(base_path()).'/public_html')) {
            $this->app->usePublicPath(dirname(base_path()).'/public_html');
        } elseif (file_exists(base_path('public_html'))) {
            $this->app->usePublicPath(base_path('public_html'));
        }
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrapFive();

        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        config()->set('livewire.temporary_file_upload.rules', [
            'required',
            'file',
            'max:'.config('uploads.max_size_kb'),
        ]);
    }
}

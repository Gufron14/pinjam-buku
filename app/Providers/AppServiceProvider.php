<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register mail view namespace for mail components
        View::addNamespace('mail', [
            resource_path('views/vendor/mail/html'),
            resource_path('views/vendor/mail/text'),
        ]);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

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
        // Reverse-proxied under /attendance/ on port 80 — without this, route()/url()
        // build links from the backend's own root and drop the /attendance prefix.
        URL::forceRootUrl(config('app.url'));
    }
}

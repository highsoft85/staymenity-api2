<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\UrlGenerator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if (config('app.redirect_https')) {
            $this->app['request']->server->set('HTTPS', true);
        }
    }

    /**
     * @param UrlGenerator $url
     */
    public function boot(UrlGenerator $url)
    {
        if (config('app.redirect_https')) {
            $url->formatScheme('https');
        }
    }
}

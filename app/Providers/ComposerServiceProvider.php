<?php

declare(strict_types=1);

namespace App\Providers;

use App\Http\Composers\AppComposer;
use App\Cmf\Composers\CmfComposer;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        View::composer('cmf.*', CmfComposer::class);
        View::composer('app.*', AppComposer::class);
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}

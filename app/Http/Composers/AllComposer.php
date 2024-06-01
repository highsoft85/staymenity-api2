<?php

declare(strict_types=1);

namespace App\Http\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Cache;

class AllComposer
{

    public function __construct()
    {
    }

    /**
     * Bind data to the view.
     *
     * @param  View $view
     * @return void
     */
    public function compose(View $view)
    {
    }

    /**
     * @param string $name
     * @param \Closure $function
     * @param int $time
     * @return mixed
     */
    private function remember($name, $function, $time = 60)
    {
        return Cache::remember($name, $time, $function);
    }
}

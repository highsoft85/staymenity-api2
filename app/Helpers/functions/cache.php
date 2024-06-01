<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;

if (!function_exists('remember')) {

    /**
     * @param string $name
     * @param \Closure $function
     * @param int $time
     * @return mixed
     */
    function remember(string $name, \Closure $function, int $time = 3600)
    {
        return Cache::remember($name, $time, $function);
    }
}

<?php

declare(strict_types=1);

if (!function_exists('healthCheckHostfully')) {

    /**
     * @return \App\Services\HealthCheck\Hostfully
     */
    function healthCheckHostfully()
    {
        return (new \App\Services\HealthCheck\Hostfully());
    }
}

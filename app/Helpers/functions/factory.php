<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Cache;

if (!function_exists('factoryModel')) {

    /**
     * @return \App\Services\Database\Factory\FactoryService
     */
    function factoryModel()
    {
        return (new \App\Services\Database\Factory\FactoryService());
    }
}

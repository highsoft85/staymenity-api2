<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Headers;

use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use Knuckles\Scribe\Extracting\ParamHelpers;
use Knuckles\Scribe\Extracting\RouteDocBlocker;
use Knuckles\Scribe\Extracting\Strategies\Strategy;
use ReflectionClass;
use ReflectionFunctionAbstract;

class PutStrategy extends Strategy
{
    use ParamHelpers;

    /**
     * The stage the strategy belongs to.
     */
    public $stage = 'headers';

    /**
     * @param Route $route
     * @param ReflectionClass $controller
     * @param ReflectionFunctionAbstract $method
     * @param array $routeRules
     * @param array $alreadyExtractedData
     * @return array|null
     */
    public function __invoke(
        Route $route,
        ReflectionClass $controller,
        ReflectionFunctionAbstract $method,
        array $routeRules,
        array $alreadyExtractedData = []
    ) {
        $isPost = in_array('PUT', $route->methods());
        if (!$isPost) {
            return null;
        }
        return [
            // поддерживает post
            'Content-Type' => 'multipart/form-data',
            // поддерживает put
            //'Content-Type' => 'application/x-www-form-urlencoded',
        ];
    }
}

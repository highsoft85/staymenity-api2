<?php

declare(strict_types=1);

namespace App\Docs;

use App\Docs\Strategies\Fields\ListingFieldsTrait;
use App\Docs\Strategies\Fields\UserFieldsTrait;
use App\Docs\Strategies\HelperStrategyTrait;
use App\Docs\Strategies\HelperResponseFieldsTrait;
use App\Docs\Strategies\ProjectListFieldsTrait;
use App\Docs\Strategies\StyleStrategyTrait;
use App\Models\User;
use Illuminate\Routing\Route;
use Knuckles\Scribe\Extracting\ParamHelpers;
use Knuckles\Scribe\Extracting\RouteDocBlocker;
use Knuckles\Scribe\Extracting\Strategies\Strategy as BaseStrategy;
use ReflectionClass;
use ReflectionFunctionAbstract;
use Tests\FactoryModelTrait;

abstract class Strategy extends BaseStrategy
{
    use ParamHelpers;
    use HelperResponseFieldsTrait;
    use HelperStrategyTrait;
    use ProjectListFieldsTrait;
    use StyleStrategyTrait;

    use ListingFieldsTrait;
    use UserFieldsTrait;
    use FactoryModelTrait;

    const STAGE_METADATA = 'metadata';
    const STAGE_RESPONSES = 'responses';
    const STAGE_RESPONSE_FIELDS = 'responseFields';
    const STAGE_QUERY_PARAMETERS = 'queryParameters';
    const STAGE_HEADERS = 'headers';
    const STAGE_BODY_PARAMETERS = 'bodyParameters';
    const STAGE_URL_PARAMETERS = 'urlParameters';

    /**
     * @var Route
     */
    protected $route;

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
        $this->route = $route;
        /** @var mixed $var */
        $var = $this;
        if (!$this->checkRoute($route, $var->getRoute())) {
            return null;
        }
        if (!method_exists($this, 'data')) {
            return null;
        }
        return $this->data();
    }

    /**
     * @return User
     */
    protected function user(): User
    {
        return User::first();
    }
}

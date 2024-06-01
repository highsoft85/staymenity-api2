<?php

declare(strict_types=1);

namespace App\Docs\Strategies\QueryParameters;

use App\Docs\Strategies\HelperStrategyTrait;
use Illuminate\Routing\Route;
use Illuminate\Support\Str;
use Knuckles\Scribe\Extracting\ParamHelpers;
use Knuckles\Scribe\Extracting\RouteDocBlocker;
use Knuckles\Scribe\Extracting\Strategies\Strategy;
use ReflectionClass;
use ReflectionFunctionAbstract;

class PaginationStrategy extends Strategy
{
    use HelperStrategyTrait;

    /**
     * Trait containing some helper methods for dealing with "parameters".
     * Useful if your strategy extracts information about parameters.
     */
    use ParamHelpers;

    /**
     * The stage the strategy belongs to.
     * One of "metadata", "urlParameters", "queryParameters", "bodyParameters", "headers", "responses", "responseFields".
     */
    public $stage = 'queryParameters';

    /**
     * @link https://scribe.readthedocs.io/en/latest/plugins.html
     * @param Route $route The route which we are currently extracting queryParameters for.
     * @param ReflectionClass $controller The class handling the current route.
     * @param ReflectionFunctionAbstract $method The method/closure handling the current route.
     * @param array $routeRules Array of rules for the ruleset which this route belongs to.
     * @param array $alreadyExtractedData Data already extracted from previous stages and earlier strategies in this stage
     *
     * See the documentation linked above for more details about writing custom strategies.
     *
     * @return array|null
     */
    public function __invoke(
        Route $route,
        ReflectionClass $controller,
        ReflectionFunctionAbstract $method,
        array $routeRules,
        array $alreadyExtractedData = []
    ) {
        $isGetRoute = in_array('GET', $route->methods());
        $isIndexRoute = strpos($route->getName(), '.index') !== false;
        $excludeRoutes = [
            $this->route_user,
            $this->route_user_payments_cards_index,
            $this->route_user_notifications_index,
            $this->route_user_devices_index,
            $this->route_user_reservations_review_index,
            $this->route_user_listings_images_index,
            $this->route_user_chats_index,
            $this->route_user_chats_messages_index,
            $this->route_user_favorites_index,
        ];
        if (in_array($route->getName(), $excludeRoutes)) {
            return null;
        }
        if ($isGetRoute && $isIndexRoute) {
            return [
                'page' => [
                    'description' => 'Текущий номер страницы, по умолчанию 1.',
                    'required' => false,
                    'value' => 1,
                ],
                'limit' => [
                    'description' => 'Максимальное количество элементов на странице.',
                    'required' => false,
                    'value' => 10,
                ],
            ];
        }
        return null;
    }
}

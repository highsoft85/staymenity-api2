<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\Index;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class HostRequestStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_BODY_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_index_host_request;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'name' => [
                'description' => 'Имя',
                'required' => true,
                'value' => 'John',
                'type' => 'string',
            ],
            'email' => [
                'description' => 'Email',
                'required' => true,
                'value' => 'john@email.com',
                'type' => 'string',
            ],
        ];
    }
}

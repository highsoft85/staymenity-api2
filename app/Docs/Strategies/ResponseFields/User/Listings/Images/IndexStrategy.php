<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Listings\Images;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class IndexStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_listings_images_index;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return [
            'id' => [
                'type' => 'int',
                'description' => 'ID изображения',
            ],
            'src' => [
                'type' => 'string',
                'description' => 'Url изображения, выводится сразу с хостом',
            ],
            'is_main' => [
                'type' => 'bool',
                'description' => 'Является ли изображение главным',
            ],
        ];
    }
}

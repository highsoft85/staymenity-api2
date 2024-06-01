<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Reservations;

use App\Docs\Strategies\Fields\ReservationStoreBodyParametersTrait;
use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class StoreStrategy extends Strategy
{
    use ReservationStoreBodyParametersTrait;

    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_BODY_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_reservations_store;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'listing_id' => $this->parameterListingId(),
            'start_at' => $this->parameterStartAt(),
            'finish_at' => $this->parameterFinishAt(),
            'guests_size' => $this->parameterGuestsSize(),
            'message' => $this->parameterMessage(),
            //'price' => $this->parameterPrice(),
            //'service_fee' => $this->parameterServiceFee(),
            //'total_price' => $this->parameterTotalPrice(),
        ];
    }
}

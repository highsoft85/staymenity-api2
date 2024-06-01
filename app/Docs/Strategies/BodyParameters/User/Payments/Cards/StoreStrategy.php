<?php

declare(strict_types=1);

namespace App\Docs\Strategies\BodyParameters\User\Payments\Cards;

use App\Docs\Strategies\Fields\PaymentCardBodyParametersTrait;
use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class StoreStrategy extends Strategy
{
    use PaymentCardBodyParametersTrait;

    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_BODY_PARAMETERS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_payments_cards_store;
    }

    /**
     * @return array
     */
    public function data()
    {
        return [
            'payment_method_id' => $this->parameterPaymentMethodId(),
        ];
    }
}

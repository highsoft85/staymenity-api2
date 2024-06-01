<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Reservations;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;

class PaymentStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_METADATA;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_reservations_payment;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'user/reservations/{id}',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => view('docs.metadata.user.reservations.payment', [])->render(),
            'authenticated' => true,
        ];
    }
}

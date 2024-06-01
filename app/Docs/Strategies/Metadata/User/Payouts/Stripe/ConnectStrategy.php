<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Payouts\Stripe;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;

class ConnectStrategy extends Strategy
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
        return $this->route_user_payouts_stripe_connect;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'user/payouts',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => view('docs.metadata.user.payouts.stripe.connect', [])->render(),
            'authenticated' => true,
        ];
    }
}

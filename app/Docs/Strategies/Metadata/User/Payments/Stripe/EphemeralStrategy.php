<?php

declare(strict_types=1);

namespace App\Docs\Strategies\Metadata\User\Payments\Stripe;

use App\Http\Transformers\Api\ListingTransformer;
use App\Models\Listing;
use App\Docs\Strategy;
use App\Models\User;

class EphemeralStrategy extends Strategy
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
        return $this->route_user_payments_stripe_ephemeral;
    }

    /**
     * @return array
     * @throws \Throwable
     */
    public function data()
    {
        return [
            'groupName' => 'user/payments/stripe',
            'groupDescription' => null,
            'title' => $this->url($this->route),
            'description' => view('docs.metadata.user.payments.stripe.ephemeral', [])->render(),
            'authenticated' => true,
        ];
    }
}

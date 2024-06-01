<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\Models\User;
use Stripe\EphemeralKey;
use Stripe\Stripe;

class PaymentEphemeralService extends BaseStripePayment
{
    use PaymentLoggerTrait;

    const TYPE = 'EPHEMERAL_CREATE';

    /**
     * @return EphemeralKey
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function getEphemeralKey()
    {
        return EphemeralKey::create(
            ['customer' => $this->customer_id],
            ['stripe_version' => Stripe::getApiVersion()]
        );
    }
}

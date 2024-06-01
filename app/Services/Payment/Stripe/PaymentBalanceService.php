<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\Models\Listing;
use App\Models\Reservation;
use App\Models\User;
use App\Services\Payment\StripeService;
use Stripe\StripeClient;

class PaymentBalanceService extends BaseStripePayment
{
    use PaymentLoggerTrait;

    const TYPE = 'BALANCE_GET';

    /**
     * @return \Stripe\Balance|null
     */
    public function get()
    {
        $this->type = self::TYPE;
        return $this->getBalance($this->oUser);
    }

    /**
     * @param User $oUser
     * @return \Stripe\Balance|null
     */
    private function getBalance(User $oUser)
    {
        try {
            $balance = $this->stripe->balance->retrieve([], [
                'stripe_account' => $this->user_stripe_account_id,
            ]);
            $this->logSuccess('SUCCESS');
            return $balance;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage());
            return null;
        }
    }
}

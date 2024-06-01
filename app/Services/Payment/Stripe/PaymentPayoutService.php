<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\Models\Listing;
use App\Models\Reservation;
use App\Services\Payment\StripeService;
use Stripe\StripeClient;

class PaymentPayoutService extends BaseStripePayment
{
    use PaymentLoggerTrait;

    const TYPE = 'PAYOUT_CREATE';

    /**
     * @return \Stripe\Payout|null
     * @throws \Exception
     */
    public function makePayout()
    {
        $this->type = self::TYPE;
        return $this->payout($this->oReservation);
    }

    /**
     * @param Reservation $oReservation
     * @return \Stripe\Payout|null
     * @throws \Exception
     */
    private function payout(Reservation $oReservation)
    {
        $oListing = $oReservation->listing;
        if (is_null($oListing)) {
            throw new \Exception('Listing is null');
        }
        $oHost = $oReservation->listing->user;
        if (is_null($oHost)) {
            throw new \Exception('Host is null');
        }
        if (is_null($this->user_stripe_account_id)) {
            throw new \Exception('Stripe account is null');
        }
        try {
            $payout = $this->stripe->payouts->create([
                'amount' => $this->getAmountInCents($oReservation->price),
                'currency' => 'usd',
                'description' => $oReservation->transferDescription,
                'metadata' => [
                    'reservation_id' => $oReservation->id,
                    'date' => $oReservation->paymentDescriptionDate,
                ],
            ], [
                'stripe_account' => $this->user_stripe_account_id,
            ]);
            $this->logSuccess('SUCCESS', $payout);
            return $payout;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage());
            return null;
        }
    }

    /**
     * @param string $payout_id
     * @return \Stripe\Payout|null
     */
    public function getPayout(string $payout_id)
    {
        $this->type = 'PAYOUT_GET';
        try {
            $payout = $this->stripe->payouts->retrieve($payout_id, [
                'stripe_account' => $this->user_stripe_account_id,
            ]);
            return $payout;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage());
            return null;
        }
    }

    /**
     * @return \Stripe\Collection|null
     */
    public function getPayouts()
    {
        $this->type = 'PAYOUT_GET_ALL';
        try {
            $payouts = $this->stripe->payouts->all([], [
                'stripe_account' => $this->user_stripe_account_id,
            ]);
            return $payouts;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage());
            return null;
        }
    }
}

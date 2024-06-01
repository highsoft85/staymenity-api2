<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\Models\Listing;
use App\Models\Reservation;
use Stripe\StripeClient;

class PaymentTransferService extends BaseStripePayment
{
    use PaymentLoggerTrait;

    const TYPE = 'TRANSFER';

    /**
     * @return \Stripe\Transfer|null
     * @throws \Exception
     */
    public function makeTransfer()
    {
        $this->type = self::TYPE;
        return $this->transfer($this->oReservation);
    }

    /**
     * @param Reservation $oReservation
     * @return \Stripe\Transfer|null
     * @throws \Exception
     */
    public function transfer(Reservation $oReservation)
    {
        $oPayment = $oReservation->payment;
        if (is_null($oPayment)) {
            throw new \Exception('Payment is null');
        }
        $oListing = $oReservation->listing;
        if (is_null($oListing)) {
            throw new \Exception('Listing is null');
        }
        if (is_null($this->host_stripe_account_id)) {
            throw new \Exception('Stripe account is null');
        }
        try {
            $amount = $oReservation->payment->amount;
            if ((int)$oPayment->service_fee !== 0) {
                $amount = $oPayment->amountWithoutService;
            }
            // комисия страйпа на стороне клиента, т.е. хост получает чистыми трасфер, без комиссии
            $transfer = $this->stripe->transfers->create([
                'amount' => $this->getAmountInCents($amount),
                'currency' => 'usd',
                'destination' => $this->host_stripe_account_id,
                'description' => $oReservation->transferDescription,
                'transfer_group' => $this->getTransferGroup(),
                'metadata' => [
                    'reservation_id' => $oPayment->reservation->id,
                ]
            ]);
            $this->logSuccess('SUCCESS');
            return $transfer;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage());
            return null;
        }
    }
}

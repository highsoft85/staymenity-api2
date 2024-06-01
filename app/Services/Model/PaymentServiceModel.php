<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\Balance;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\Reservation;
use App\Models\User;
use Stripe\PaymentIntent;

class PaymentServiceModel
{
    /**
     * @param User $oFrom
     * @param User $oTo
     * @param Listing $oListing
     * @param PaymentIntent $payment
     * @return Payment
     */
    public function createByPaymentIntend(User $oFrom, User $oTo, Listing $oListing, PaymentIntent $payment)
    {
        $amount = $payment->amount / 100;
        $serviceFee = !$oListing->isFreeService()
            ? Reservation::SERVICE_FEE
            : 0;

        $oPayment = Payment::create([
            'provider' => Payment::PROVIDER_STRIPE,
            'provider_payment_id' => $payment->id,
            'user_from_id' => $oFrom->id,
            'user_to_id' => $oTo->id,
            'amount' => $amount,
            'service_fee' => $serviceFee,
            'status' => Payment::STATUS_ACTIVE,
        ]);

        // баланс только из страйпа
        //(new BalanceServiceModel())->updateBalance($oTo);

        return $oPayment;
    }

    /**
     * @param Payment $oPayment
     * @param User $oTo
     * @return Payment
     */
    public function cancelByPaymentIntend(Payment $oPayment, User $oTo)
    {
        $oPayment->update([
            'status' => Payment::STATUS_CANCELLED,
        ]);
        // баланс только из страйпа
        //(new BalanceServiceModel())->updateBalance($oTo);
        return $oPayment;
    }
}

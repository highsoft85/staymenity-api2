<?php

declare(strict_types=1);

namespace App\Services\Model\Reservation;

use App\Models\Reservation;
use App\Models\User;
use App\Services\Model\PaymentServiceModel;
use App\Services\Payment\Stripe\PaymentIntendService;
use Stripe\PaymentIntent;

trait ReservationPaymentTrait
{
    /**
     * @param User $oUser
     * @param User $oHost
     * @param string $method
     * @return \App\Models\Payment
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function paymentByMethod(User $oUser, User $oHost, string $method)
    {
        $oService = (new PaymentIntendService())
            ->setUser($oUser)
            ->setReservation($this->oReservation)
            ->setPaymentMethod($method);

        // совершение платежа
        $payment = $oService->makePayment();

        if (is_null($payment)) {
            throw new \Exception($oService->getMessage());
        }

        // сохранение платежки, обновление баланса
        return $this->afterPayment($oUser, $oHost, $payment);
    }

    /**
     * @param User $oUser
     * @param User $oHost
     * @param PaymentIntent $payment
     * @return \App\Models\Payment
     */
    private function afterPayment(User $oUser, User $oHost, PaymentIntent $payment)
    {
        $oListing = $this->oReservation->listing;
        // сохранение платежки, обновление баланса
        $oPayment = (new PaymentServiceModel())->createByPaymentIntend($oUser, $oHost, $oListing, $payment);
        $this->oReservation->update([
            'payment_id' => $oPayment->id,
            'status' => Reservation::STATUS_ACCEPTED,
            'accepted_at' => now(),
        ]);
        return $oPayment;
    }
}

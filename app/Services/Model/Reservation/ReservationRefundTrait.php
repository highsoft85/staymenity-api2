<?php

declare(strict_types=1);

namespace App\Services\Model\Reservation;

use App\Models\PaymentCharge;
use App\Models\Reservation;
use App\Services\Model\PaymentServiceModel;
use App\Services\Payment\Stripe\PaymentRefundService;

trait ReservationRefundTrait
{
    /**
     * @param bool $isDecline
     * @return \App\Models\Payment|null
     * @throws \Exception
     */
    public function cancelPayment(bool $isDecline = false)
    {
        $oPayment = $this->oReservation->payment;
        if (!is_null($oPayment)) {
            $oUser = $oPayment->userFrom;
            $oHost = $oPayment->userTo;
            // совершение платежа
            $oService = (new PaymentRefundService())
                ->setUser($oUser)
                ->setReservation($this->oReservation);

            if (!$isDecline) {
                $isFreeCancellation = now() < $this->oReservation->free_cancellation_at;
                if (!$isFreeCancellation) {
                    $oService->setFeeForCancellation((float)Reservation::CANCELLATION_CHARGE);
                }
            }

            if ($oPayment->provider_payment_id !== stripePaymentIntendTest()) {
                $payment = $oService->cancelPayment($oPayment->provider_payment_id);

                if (is_null($payment)) {
                    throw new \Exception($oService->getMessage());
                }
            }

            if (!$isDecline) {
                if (isset($isFreeCancellation) && !$isFreeCancellation) {
                    $oPayment->charges()->create([
                        'type' => PaymentCharge::TYPE_CANCELLATION,
                        'amount' => Reservation::CANCELLATION_CHARGE,
                    ]);
                }
            }

            // сохранение платежки, обновление баланса
            $oPayment = (new PaymentServiceModel())->cancelByPaymentIntend($oPayment, $oHost);
        }
        return $oPayment;
    }
}

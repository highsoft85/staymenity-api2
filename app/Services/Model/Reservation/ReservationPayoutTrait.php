<?php

declare(strict_types=1);

namespace App\Services\Model\Reservation;

use App\Models\User;
use App\Services\Model\PayoutServiceModel;
use App\Services\Payment\Stripe\PaymentPayoutService;
use Stripe\Payout as PayoutIntend;

trait ReservationPayoutTrait
{
    /**
     * @return \App\Models\Payout
     * @throws \Exception
     */
    public function makePayout()
    {
        $oUser = $this->oReservation->listing->user;

        $oService = (new PaymentPayoutService())
            ->setUser($oUser)
            ->setReservation($this->oReservation);

        // совершение выплаты
        $payout = $oService->makePayout();

        if (is_null($payout)) {
            throw new \Exception($oService->getMessage());
        }

        // сохранение платежки, обновление баланса
        return $this->afterPayout($oUser, $payout);
    }

    /**
     * @param User $oUser
     * @param PayoutIntend $payout
     * @return \App\Models\Payout
     */
    private function afterPayout(User $oUser, PayoutIntend $payout)
    {
        $oPayout = (new PayoutServiceModel())->createByPayoutIntend($oUser, $payout);
        $this->oReservation->update([
            'payout_id' => $oPayout->id,
            'payout_at' => now(),
        ]);
        return $oPayout;
    }
}

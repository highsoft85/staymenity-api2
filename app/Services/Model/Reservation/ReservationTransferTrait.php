<?php

declare(strict_types=1);

namespace App\Services\Model\Reservation;

use App\Models\User;
use App\Services\Model\TransferServiceModel;
use App\Services\Payment\Stripe\PaymentIntendService;
use App\Services\Payment\Stripe\PaymentTransferService;
use Stripe\Transfer;

trait ReservationTransferTrait
{
    /**
     * @return \App\Models\Transfer
     * @throws \Exception
     */
    public function makeTransfer()
    {
        $oUser = $this->oReservation->listing->user;

        $oService = (new PaymentTransferService())
            ->setUser($oUser)
            ->setReservation($this->oReservation);

        // совершение трансфера
        $transfer = $oService->makeTransfer();

        if (is_null($transfer)) {
            throw new \Exception($oService->getMessage());
        }

        // сохранение трансфера
        $oTransfer = $this->afterTransfer($oUser, $transfer);
        $this->oReservation->refresh();

        // сохранение информации в платежке
        $oService = (new PaymentIntendService())
            ->setReservation($this->oReservation)
            ->updateWithTransfer($this->oReservation);

        return $oTransfer;
    }

    /**
     * @param User $oUser
     * @param Transfer $transfer
     * @return \App\Models\Transfer
     */
    private function afterTransfer(User $oUser, Transfer $transfer)
    {
        $oTransfer = (new TransferServiceModel())->createByTransferIntend($oUser, $transfer);
        $this->oReservation->update([
            'transfer_id' => $oTransfer->id,
            'transfer_at' => now(),
        ]);
        return $oTransfer;
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\Balance;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\Reservation;
use App\Models\Transfer;
use App\Models\User;
use Stripe\PaymentIntent;
use Stripe\Payout as PayoutIntend;
use Stripe\Transfer as TransferIntend;

class TransferServiceModel
{
    /**
     * @param User $oUser
     * @param TransferIntend $transfer
     * @return Transfer
     */
    public function createByTransferIntend(User $oUser, TransferIntend $transfer)
    {
        $amount = $transfer->amount / 100;

        $oTransfer = Transfer::create([
            'provider' => Transfer::PROVIDER_STRIPE,
            'provider_transfer_id' => $transfer->id,
            'user_id' => $oUser->id,
            'amount' => $amount,
            'status' => Transfer::STATUS_COMPLETED,
        ]);
        return $oTransfer;
    }
}

<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\Balance;
use App\Models\Listing;
use App\Models\Payment;
use App\Models\Payout;
use App\Models\Reservation;
use App\Models\User;
use Stripe\PaymentIntent;
use Stripe\Payout as PayoutIntend;

class PayoutServiceModel
{
    /**
     * @param User $oUser
     * @param PayoutIntend $payout
     * @return Payout
     */
    public function createByPayoutIntend(User $oUser, PayoutIntend $payout)
    {
        $amount = $payout->amount / 100;

        $oPayment = Payout::create([
            'provider' => Payment::PROVIDER_STRIPE,
            'provider_payout_id' => $payout->id,
            'provider_transaction_id' => $payout->balance_transaction,
            'user_id' => $oUser->id,
            'amount' => $amount,
            'status' => Payout::STATUS_PENDING,
        ]);
        return $oPayment;
    }

    /**
     * @param Payout $oPayout
     * @return Payout
     */
    public function cancelByPaymentIntend(Payout $oPayout)
    {
        $oPayout->update([
            'status' => Payout::STATUS_CANCELLED,
        ]);
        return $oPayout;
    }
}

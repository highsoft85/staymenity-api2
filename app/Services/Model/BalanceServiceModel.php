<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\Balance;
use App\Models\Payment;
use App\Models\User;
use Stripe\PaymentIntent;

class BalanceServiceModel
{
    /**
     * @param User $oUser
     * @return Balance|\Illuminate\Database\Eloquent\Model
     */
    public function createEmptyBalance(User $oUser)
    {
        return $oUser->balance()->create([
            'amount' => 0,
            'status' => Balance::STATUS_ACTIVE,
        ]);
    }

    /**
     * @param User $oUser
     * @return Balance
     */
    public function updateBalance(User $oUser)
    {
        /** @var Balance|null $oBalance */
        $oBalance = $oUser->balance;
        if (is_null($oBalance)) {
            $oBalance = $this->createEmptyBalance($oUser);
        }
        $amount = $oUser->paymentsToMe()->active()->sum('amount');
        $serviceFee = $oUser->paymentsToMe()->active()->sum('service_fee');

        $oBalance->update([
            'amount' => $amount - $serviceFee,
        ]);

        return $oBalance;
    }
}

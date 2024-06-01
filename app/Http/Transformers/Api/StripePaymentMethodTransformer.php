<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Stripe\Card;
use Stripe\PaymentMethod;

class StripePaymentMethodTransformer
{
    /**
     * @param PaymentMethod $oItem
     * @return array
     */
    public function transform(PaymentMethod $oItem)
    {
        /** @var Card $card */
        $card = $oItem->card;
        $brandTitle = $card->brand;
        $brandName = Str::lower($brandTitle);

        $icon = '/img/payments/cards/' . $brandName . '.png';
//        $iconPath = public_path($icon);
//        if (!file_exists($iconPath)) {
//
//        }
        return [
            'id' => Crypt::encryptString($oItem->id),
            'payment_method_id' => $oItem->id,
            'icon' => imageWithDomain($icon),
            'brand' => Str::ucfirst($brandTitle),
            'last' => $card->last4,
            'exp_month' => $card->exp_month,
            'exp_year' => $card->exp_year,
            'is_main' => false,
        ];
    }
}

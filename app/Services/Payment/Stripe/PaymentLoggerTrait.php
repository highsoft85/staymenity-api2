<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\Models\Reservation;
use App\Models\User;
use App\Services\Notification\Slack\SlackDebugNotification;
use Illuminate\Support\Facades\Log;
use Stripe\PaymentIntent;
use Stripe\Payout as PayoutIntend;

trait PaymentLoggerTrait
{
    /**
     * @param string $message
     */
    protected function logError(string $message): void
    {
        if (config('logging.channels.slack-debug.stripe_enabled')) {
            (new SlackDebugNotification())->send($this->logData($this->type, $message));
        }
        Log::channel('stack-stripe')->error($this->logData($this->type, $message));
    }

    /**
     * @param null|PaymentIntent|PayoutIntend|mixed $stripeObject
     * @param string $message
     */
    protected function logSuccess(string $message, $stripeObject = null)
    {
        if (config('logging.channels.slack-debug.stripe_enabled')) {
            (new SlackDebugNotification())->send($this->logData($this->type, $message, $stripeObject));
        }
        Log::channel('stack-stripe')->info($this->logData($this->type, $message, $stripeObject));
    }

    /**
     * @param string $text
     * @param string $message
     * @param null|PaymentIntent|PayoutIntend|mixed $stripeObject
     * @return string
     */
    private function logData(string $text, string $message, $stripeObject = null)
    {
        /** @var User|null $oUser */
        $oUser = $this->oUser;
        /** @var Reservation|null $oReservation */
        $oReservation = $this->oReservation;

        $text .= ': ' . $message;
        if (!is_null($oReservation)) {
            $price = $oReservation->price;
            $totalPrice = $oReservation->total_price;
            $array = [];
            $array['type'] = $this->type;
            $array['reservation_id'] = $oReservation->id;
            $array['price'] = $price;
            $array['amount'] = $totalPrice;
            $array['amount_cents'] = $this->getAmountInCents($totalPrice);
            if (isset($this->feeCancellation) && !is_null($this->feeCancellation)) {
                $array['charge'] = $this->feeCancellation;
            }
        }
        if (!is_null($oUser)) {
            $array['user']['id'] = $oUser->id;
            $array['user']['email'] = $oUser->email;
            $array['user']['first_name'] = $oUser->first_name;
            $array['user']['last_name'] = $oUser->last_name;
        }
        $array['customer_id'] = $this->customer_id;
        if (!is_null($stripeObject)) {
            if ($stripeObject instanceof PaymentIntent) {
                $aPayment = $this->logPaymentIntentData($stripeObject);
                $array['payment_intent'] = json_encode($aPayment);
            }
            if ($stripeObject instanceof PayoutIntend) {
                $aPayment = $this->logPayoutIntentData($stripeObject);
                $array['payout_intent'] = json_encode($aPayment);
            }
            if (is_string($stripeObject)) {
                $array['stripe_item'] = $stripeObject;
            }
        }
        $text .= ' ' . json_encode($array);
        return $text;
    }

    /**
     * @param PaymentIntent $payment
     * @return array
     */
    private function logPaymentIntentData(PaymentIntent $payment)
    {
        return [
            'id' => $payment->id,
            'capture_method' => $payment->capture_method,
            'confirmation_method' => $payment->confirmation_method,
            'metadata' => $payment->metadata,
            'status' => $payment->status,
        ];
    }

    /**
     * @param PayoutIntend $payout
     * @return array
     */
    private function logPayoutIntentData(PayoutIntend $payout)
    {
        return [
            'id' => $payout->id,
            'amount' => $payout->amount,
            'metadata' => $payout->metadata,
            'status' => $payout->status,
        ];
    }
}

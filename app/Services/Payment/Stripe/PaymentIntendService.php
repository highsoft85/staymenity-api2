<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\Models\Reservation;
use App\Models\Transfer;
use App\Models\User;
use Stripe\PaymentIntent;

class PaymentIntendService extends BaseStripePayment
{
    use PaymentLoggerTrait;

    const TYPE = 'PAYMENT_CREATE';

    /**
     * @var string|null
     */
    protected $payment_method_id;

    /**
     * @param string $payment_method_id
     * @return $this
     */
    public function setPaymentMethod(string $payment_method_id)
    {
        $this->payment_method_id = $payment_method_id;
        return $this;
    }

    /**
     * Основной метод оплаты
     *
     * @return PaymentIntent|null
     */
    public function makePayment()
    {
        $this->type = self::TYPE;
        return $this->makePaymentIntend($this->oReservation, $this->customer_id, $this->payment_method_id);
    }

    /**
     * @param Reservation $oReservation
     * @param string $customer_id
     * @param string $payment_method_id
     * @return PaymentIntent|null
     */
    private function makePaymentIntend(Reservation $oReservation, string $customer_id, string $payment_method_id)
    {
        try {
            // достать все карточки
            $oPaymentMethod = (new PaymentMethodService())->setUser($this->oUser);
            $oCards = $oPaymentMethod->getPaymentMethods();
            // добавить карточку если нет
            if (!$oPaymentMethod->hasMethodByCards($oCards, $payment_method_id)) {
                $oPaymentMethod->paymentMethodAttach($customer_id, $payment_method_id);
            }
            $transferData = [];
//            if (!is_null($this->host_stripe_account_id)) {
//                $transferData['destination'] = $this->host_stripe_account_id;
//                // без service_fee, только за сдачу по часам
//                $transferData['amount'] = $this->getAmountInCents($oReservation->price);
//            }
            $payment = $this->stripe->paymentIntents->create([
                'currency' => 'usd',
                'amount' => $this->getAmountInCents($oReservation->total_price),
                'customer' => $customer_id,
                'payment_method' => $payment_method_id,
                'description' => $oReservation->paymentDescription,
                'confirm' => true,
                'off_session' => true,
                'metadata' => [
                    'reservation_id' => $oReservation->id,
                    'date' => $oReservation->paymentDescriptionDate,
                ],
                // сразу пойдет платеж на страйп хоста
                'transfer_data' => $transferData,
                'transfer_group' => $this->getTransferGroup(),
                //'on_behalf_of' => '{{CONNECTED_STRIPE_ACCOUNT_ID}}'
            ]);
            // установить главной, если не главная
            if (!$oPaymentMethod->isMainMethodByCards($oCards, $payment_method_id)) {
                $oPaymentMethod->paymentMethodSetDefault($customer_id, $payment_method_id);
            }
            $this->logSuccess(self::TYPE . ' SUCCESS', $payment);
            return $payment;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage() . ':' . $e->getFile() . ':' . $e->getLine());
            return null;
        }
    }

    /**
     * @param Reservation $oReservation
     * @return null
     */
    public function updateWithTransfer(Reservation $oReservation)
    {
        $this->type = 'PAYMENT_UPDATE';
        $oPayment = $oReservation->payment;
        try {
            $this->stripe->paymentIntents->update($oPayment->provider_payment_id, [
                'metadata' => [
                    'reservation_id' => $oReservation->id,
                    'date' => $oReservation->paymentDescriptionDate,
                    'transfer_at' => $oReservation->transfer_at->toIso8601String(),
                    'transfer_amount' => $oReservation->transfer->amount,
                    'transfer_email' => $oReservation->transfer->user->email,
                ],
            ]);
            $this->logSuccess('SUCCESS');
            return null;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage() . ':' . $e->getFile() . ':' . $e->getLine());
            return null;
        }
    }
}

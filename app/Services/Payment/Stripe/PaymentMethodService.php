<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\Http\Transformers\Api\StripePaymentMethodTransformer;
use Stripe\Card;
use Stripe\PaymentMethod;

class PaymentMethodService extends BaseStripePayment
{
    use PaymentLoggerTrait;

    /**
     * @return array
     */
    public function getPaymentMethods()
    {
        try {
            $oCustomer = $this->getCustomer();
            $cards = $this->stripe->paymentMethods->all([
                'customer' => $this->customer_id,
                'type' => 'card',
            ]);
            $aCards = [];
            $defaultPaymentMethodId = $oCustomer->invoice_settings->default_payment_method ?? null;
            $cards = $cards->data;
            /** @var PaymentMethod[] $cards */
            foreach ($cards as $paymentMethod) {
                $data = (new StripePaymentMethodTransformer())->transform($paymentMethod);
                if ($paymentMethod->id === $defaultPaymentMethodId) {
                    $data['is_main'] = true;
                }
                $aCards[] = $data;
            }
            return $aCards;
        } catch (\Exception $e) {
            $this->logError('Get payment methods ERROR  ' . $e->getMessage());
            return [];
        }
    }

    /**
     * @param string $customer_id
     * @param string $paymentMethod
     * @return bool
     */
    public function hasPaymentMethod(string $customer_id, string $paymentMethod)
    {
        try {
            $cards = $this->stripe->paymentMethods->all([
                'customer' => $customer_id,
                'type' => 'card',
            ]);
            $hasCard = false;
            foreach ($cards as $card) {
                if ($card->id === $paymentMethod) {
                    $hasCard = true;
                    break;
                }
            }
            return $hasCard;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * @param array $oCards
     * @param string $paymentMethod
     * @return bool
     */
    public function hasMethodByCards(array $oCards, string $paymentMethod)
    {
        foreach ($oCards as $oCard) {
            if ($oCard['payment_method_id'] === $paymentMethod) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param array $oCards
     * @param string $paymentMethod
     * @return bool
     */
    public function isMainMethodByCards(array $oCards, string $paymentMethod)
    {
        foreach ($oCards as $oCard) {
            if ($oCard['payment_method_id'] === $paymentMethod && $oCard['is_main']) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $customer_id
     * @param string $paymentMethod
     * @return string|null
     */
    public function paymentMethodAttach(string $customer_id, string $paymentMethod)
    {
        $this->type = 'METHOD_ATTACH';
        try {
            $cards = $this->stripe->paymentMethods->all([
                'customer' => $customer_id,
                'type' => 'card',
            ]);
            $hasCard = false;
            foreach ($cards as $card) {
                if ($card->id === $paymentMethod) {
                    $hasCard = true;
                    break;
                }
            }
            if (!$hasCard) {
                $this->stripe->paymentMethods->attach($paymentMethod, [
                    'customer' => $customer_id,
                ]);
            }
            $this->logSuccess('Method attach SUCCESS ' . $paymentMethod);
            return $paymentMethod;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError('Method attach ERROR  ' . $paymentMethod . ' ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @param string $customer_id
     * @param string $paymentMethod
     * @return string|null
     */
    public function paymentMethodDetach(string $customer_id, string $paymentMethod)
    {
        $this->type = 'METHOD_DETACH';
        try {
            $cards = $this->getPaymentMethods();
            if (!empty($cards)) {
                $deleteIsMain = false;
                $otherMain = null;
                foreach ($cards as $card) {
                    if ($card['payment_method_id'] === $paymentMethod && $card['is_main']) {
                        $deleteIsMain = true;
                    }
                    if ($card['payment_method_id'] !== $paymentMethod && !$card['is_main']) {
                        $otherMain = $card['payment_method_id'];
                    }
                }
                $this->stripe->paymentMethods->detach($paymentMethod);
                // если была удалена дефолтная, то поставится первая найденная
                if ($deleteIsMain && !is_null($otherMain)) {
                    $this->paymentMethodSetDefault($otherMain, $customer_id);
                }
            }
            $this->logSuccess('Method detach SUCCESS  ' . $paymentMethod);
            return $paymentMethod;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError('Method detach ERROR  ' . $paymentMethod . ' ' . $e->getMessage());
            return null;
        }
    }

    /**
     * @param string $customer_id
     * @param string $paymentMethod
     * @return string|null
     */
    public function paymentMethodSetDefault(string $customer_id, string $paymentMethod)
    {
        $this->type = 'METHOD_SET_DEFAULT';
        try {
            $this->stripe->customers->update($customer_id, [
                'invoice_settings' => [
                    'default_payment_method' => $paymentMethod,
                ],
            ]);
            $this->logSuccess('Method set default SUCCESS  ' . $paymentMethod);
            return $paymentMethod;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError('Method set default ERROR  ' . $paymentMethod . ' ' . $e->getMessage());
            return null;
        }
    }


    /**
     * Создание метода оплаты, что на web делается через js
     *
     * @param int $number
     * @param int $month
     * @param int $year
     * @param int $cvc
     * @return \Stripe\PaymentMethod|null
     */
    public function createPaymentMethodCard(int $number, int $month, int $year, int $cvc)
    {
        try {
            $data['type'] = 'card';
            $data['card'] = [
                'number' => $number,
                'exp_month' => $month,
                'exp_year' => $year,
                'cvc' => $cvc,
            ];
            return $this->stripe->paymentMethods->create($data);
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            return null;
        }
    }


    /**
     * Создание метода оплаты, что на web делается через js
     *
     * @param int $number
     * @param int $month
     * @param int $year
     * @param int $cvc
     * @return \Stripe\Token|null
     */
    public function tokenCreate(int $number, int $month, int $year, int $cvc)
    {
        try {
            $data['card'] = [
                'number' => $number,
                'exp_month' => $month,
                'exp_year' => $year,
                'cvc' => $cvc,
                'currency' => 'usd',
            ];
            return $this->stripe->tokens->create($data);
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            return null;
        }
    }
}

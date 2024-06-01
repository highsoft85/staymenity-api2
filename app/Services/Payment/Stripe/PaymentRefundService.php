<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use Stripe\Refund;

class PaymentRefundService extends BaseStripePayment
{
    use PaymentLoggerTrait;

    const TYPE = 'PAYMENT_REFUND';

    /**
     * @var float|null
     */
    private $feeCancellation = null;

    /**
     * @param float $amount
     * @return $this
     */
    public function setFeeForCancellation(float $amount)
    {
        $this->feeCancellation = $amount;

        return $this;
    }

    /**
     * Основной метод отмены оплаты
     *
     * @param string $payment_intend_id
     * @return Refund|null
     */
    public function cancelPayment(string $payment_intend_id)
    {
        $this->type = self::TYPE;
        return $this->makeRefundPaymentIntend($payment_intend_id);
    }

    /**
     * @param string $payment_intend_id
     * @return Refund|null
     */
    private function makeRefundPaymentIntend(string $payment_intend_id)
    {
        try {
            $data = [
                'payment_intent' => $payment_intend_id,
            ];
            if (!is_null($this->feeCancellation)) {
                $data['amount'] = $this->getAmountInCents($this->feeCancellation);
            }
            $refund = $this->stripe->refunds->create($data);
            $this->logSuccess(self::TYPE . ' SUCCESS');
            return $refund;
        } catch (\Exception $e) {
            $this->logError($e->getMessage());
        }
        return null;
    }
}

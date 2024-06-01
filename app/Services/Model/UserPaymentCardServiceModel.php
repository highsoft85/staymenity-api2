<?php

declare(strict_types=1);

namespace App\Services\Model;

use App\Models\User;
use App\Services\Payment\Stripe\PaymentMethodService;

class UserPaymentCardServiceModel
{
    /**
     * @var User
     */
    private $oUser;

    /**
     * @var PaymentMethodService
     */
    private $stripeService;

    /**
     * @var string
     */
    private $customer_id;

    /**
     * UserPaymentCardServiceModel constructor.
     * @param User $oUser
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function __construct(User $oUser)
    {
        $this->oUser = $oUser;
        $this->stripeService = (new PaymentMethodService())
            ->setUser($oUser);
        $this->customer_id = $this->stripeService->getCustomerId();
    }

    /**
     * @param string $paymentMethod
     * @throws \Exception
     */
    public function create(string $paymentMethod)
    {
        $this->attach($paymentMethod);
        $this->setMain($paymentMethod);
    }

    /**
     * @param string $paymentMethod
     * @throws \Exception
     */
    public function attach(string $paymentMethod)
    {
        $this->stripeService->paymentMethodAttach($this->customer_id, $paymentMethod);
        if ($this->stripeService->hasError()) {
            throw new \Exception($this->stripeService->getMessage());
        }
    }

    /**
     * @param string $paymentMethod
     * @throws \Exception
     */
    public function detach(string $paymentMethod)
    {
        $this->stripeService->paymentMethodDetach($this->customer_id, $paymentMethod);
        if ($this->stripeService->hasError()) {
            throw new \Exception($this->stripeService->getMessage());
        }
    }

    /**
     * @param string $paymentMethod
     * @throws \Exception
     */
    public function setMain(string $paymentMethod)
    {
        $this->stripeService->paymentMethodSetDefault($this->customer_id, $paymentMethod);
        if ($this->stripeService->hasError()) {
            throw new \Exception($this->stripeService->getMessage());
        }
    }
}

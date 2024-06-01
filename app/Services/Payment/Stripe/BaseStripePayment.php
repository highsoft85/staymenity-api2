<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\Models\Reservation;
use App\Models\User;
use App\Services\Environment;
use App\Services\Model\UserServiceModel;
use Stripe\Customer;
use Stripe\Stripe;
use Stripe\StripeClient;

abstract class BaseStripePayment
{

    /**
     * @var StripeClient
     */
    protected $stripe;

    /**
     * @var User
     */
    protected $oUser;

    /**
     * @var Reservation|null
     */
    protected $oReservation;

    /**
     * @var string|null
     */
    protected $customer_id;

    /**
     * @var string|null
     */
    protected $user_stripe_account_id;

    /**
     * @var string|null
     */
    protected $host_stripe_account_id;

    /**
     * @var string|null
     */
    protected $message = null;

    /**
     * @var string
     */
    protected $type = '';

    /**
     * StripePaymentService constructor.
     */
    public function __construct()
    {
        if (config('app.env') === 'production') {
            $key = config('services.stripe.secret_key');
        } else {
            $key = config('services.stripe.test_secret_key');
        }
        $version = config('services.stripe.version');

        Stripe::setApiKey($key);
        Stripe::setApiVersion($version);

        $this->stripe = new StripeClient($key);
    }

    /**
     * @param User $oUser
     * @return $this
     */
    public function setUser(User $oUser)
    {
        $this->oUser = $oUser;
        if (is_null($this->oUser->details)) {
            $this->oUser->details()->create();
            $this->oUser->refresh();
        }
        $oDetails = $this->oUser->details;

        $this->customer_id = !is_null($oDetails->customerValue)
            ? $oDetails->customerValue
            : $this->createCustomer();

        $this->user_stripe_account_id = !is_null($oDetails->stripeAccountValue)
            ? $oDetails->stripeAccountValue
            : $this->createStripeAccount();

        return $this;
    }

    /**
     * @param Reservation $oReservation
     * @return $this
     */
    public function setReservation(Reservation $oReservation)
    {
        $this->oReservation = $oReservation;

        $oHost = $this->oReservation->listing->user;
        if (is_null($oHost->details)) {
            $oHost->details()->create();
            $oHost->refresh();
        }
        $oDetails = $oHost->details;

        $this->host_stripe_account_id = !is_null($oDetails->stripeAccountValue)
            ? $oDetails->stripeAccountValue
            : $this->createStripeAccount();

        return $this;
    }

    /**
     * @return string|null
     */
    private function createCustomer()
    {
        $oUser = $this->oUser;
        $name = $this->oUser->fullName;

        if (config('app.env') === Environment::TESTING) {
            $name .= ' (' . Environment::TESTING . ')';
        }
        $classes = [
            PaymentEphemeralService::class,
            PaymentIntendService::class,
            PaymentMethodService::class,
        ];
        $customer = null;
        // создать кастомера для юзера только если нужен в классе
        if (in_array(get_class($this), $classes)) {
            $customer = Customer::create([
                'phone' => $oUser->phone,
                'email' => $oUser->email,
                'name' => $name,
                'metadata' => [
                    'user_id' => $oUser->id,
                ],
            ]);
        }
        if (config('app.env') === Environment::PRODUCTION) {
            $save['customer_id'] = $customer->id ?? null;
        } else {
            $save['test_customer_id'] = $customer->id ?? null;
        }
        $this->oUser->details()->update($save);
        $this->oUser->refresh();
        return $customer->id ?? null;
    }


    /**
     * @param float|int $price
     * @return int
     */
    protected function getAmountInCents($price)
    {
        $value = $price * 100;
        return (int)$value;
    }

    /**
     * @return string
     */
    protected function getTransferGroup()
    {
        return 'RESERVATION_' . $this->oReservation->id;
    }

    /**
     * @return Customer
     * @throws \Stripe\Exception\ApiErrorException
     */
    public function getCustomer()
    {
        return $this->stripe->customers->retrieve($this->customer_id, []);
    }

    /**
     * @return string|null
     */
    public function getCustomerId()
    {
        return $this->customer_id;
    }

    /**
     * @return string|null
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return !is_null($this->message);
    }

    /**
     * @return string|null
     */
    private function createStripeAccount()
    {
        return (new UserServiceModel($this->oUser))->setStripeAccount(null);
    }

    /**
     * @param string $account
     * @return string|null
     */
    protected function saveUserAccount(string $account)
    {
        return (new UserServiceModel($this->oUser))->setStripeAccount($account);
    }
}

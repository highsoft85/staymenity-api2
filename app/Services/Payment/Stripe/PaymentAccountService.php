<?php

declare(strict_types=1);

namespace App\Services\Payment\Stripe;

use App\Models\User;
use App\Services\Payment\StripeService;
use Stripe\Account;
use Stripe\AccountLink;

class PaymentAccountService extends BaseStripePayment
{
    use PaymentLoggerTrait;

    const TYPE = 'ACCOUNT_CREATE';

    /**
     * @param User $oUser
     * @return string
     */
    private function getRedirectUrl(User $oUser)
    {
        return config('app.web_url') . route('web.payout.connect.success', [
                'token' => $oUser->emailToken,
            ], false);
    }

    /**
     * @param string $action
     * @param string $message
     * @return string
     */
    public function typeWithAction(string $action, string $message)
    {
        return self::TYPE . ' ' . $action . ': ' . $message;
    }

    /**
     * @return Account|null
     */
    public function create()
    {
        $this->type = self::TYPE;
        return $this->createExpressAccount($this->oUser);
    }

    /**
     * @param User $oUser
     * @return Account|null
     */
    private function createExpressAccount(User $oUser)
    {
        $this->type = 'ACCOUNT_CREATE';
        try {
            $name = $oUser->fullName;
            if (envIsTesting()) {
                $name .= ' (testing)';
            }
            $account = $this->stripe->accounts->create([
                'email' => $oUser->email,
                'country' => 'US',
                'type' => 'express',
                'capabilities' => [
                    'transfers' => ['requested' => true],
                ],
                'business_type' => 'individual',
                'individual' => [
                    'email' => $oUser->email,
                    'first_name' => $oUser->first_name,
                    'last_name' => $oUser->last_name,
                    'phone' => $oUser->phone,
                ],
                'business_profile' => [
                    // https://stripe.com/docs/connect/setting-mcc
                    // Real Estate Agents and Managers - Rentals 6513
                    'mcc' => '6513',
                    'name' => $name,
                    'support_email' => $oUser->email,
                    'url' => $oUser->getHostUrl(),
                ],
                'metadata' => [
                    'user_id' => $oUser->id,
                    'env' => config('app.env'),
                ],
                'settings' => [
                    'payouts' => [
                        'schedule' => [
                            'interval' => 'manual',
                        ],
                    ],
                ],
            ]);
            $this->saveUserAccount($account->id);
            $this->logSuccess('SUCCESS');
            return $account;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage());
            return null;
        }
    }

    /**
     * @return AccountLink|null
     */
    public function getExpressLink()
    {
        $this->type = 'ACCOUNT_EXPRESS_LINK';
        try {
            $link = $this->stripe->accountLinks->create([
                'account' => $this->user_stripe_account_id,
                'refresh_url' => 'https://connect.stripe.com/hosted/oauth',
                'return_url' => $this->getRedirectUrl($this->oUser),
                'type' => 'account_onboarding',
            ]);
            $this->logSuccess('SUCCESS');
            return $link;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage());
            return null;
        }
    }

    /**
     * @return \Stripe\LoginLink|null
     */
    public function getLoginLink()
    {
        $this->type = 'ACCOUNT_LOGIN_LINK';
        try {
            $link = $this->stripe->accounts->createLoginLink($this->user_stripe_account_id, []);
            $this->logSuccess('SUCCESS');
            return $link;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage());
            return null;
        }
    }


    /**
     * @return Account|null
     */
    public function get()
    {
        $this->type = 'ACCOUNT_GET';
        try {
            $account = $this->stripe->accounts->retrieve($this->user_stripe_account_id, []);
            $this->logSuccess('SUCCESS');
            return $account;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage());
            return null;
        }
    }

    /**
     * @return bool
     */
    public function accountIsEnabled()
    {
        $account = $this->get();
        if (is_null($account)) {
            return false;
        }
        if ($account->charges_enabled && $account->details_submitted && $account->payouts_enabled) {
            return true;
        }
        return false;
    }

    /**
     * @param string $account_id
     * @return Account|null
     */
    public function remove(string $account_id)
    {
        $this->type = 'ACCOUNT_REMOVE';
        try {
            $account = $this->stripe->accounts->delete($account_id, []);
            $this->logSuccess('SUCCESS', $account_id);
            return $account;
        } catch (\Exception $e) {
            $this->message = $e->getMessage();
            $this->logError($e->getMessage());
            return null;
        }
    }
}

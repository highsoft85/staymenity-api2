<?php

declare(strict_types=1);

namespace App\Docs\Strategies\ResponseFields\User\Payouts\Stripe;

use App\Http\Transformers\Api\PaymentTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Http\Transformers\Api\UserTransformer;
use App\Models\Payment;
use App\Models\User;
use App\Docs\Strategy;
use App\Models\UserSave;
use App\Notifications\User\LeaveReviewNotification;

class DashboardStrategy extends Strategy
{
    /**
     * The stage the strategy belongs to.
     */
    public $stage = self::STAGE_RESPONSE_FIELDS;

    /**
     * @return string
     */
    public function getRoute()
    {
        return $this->route_user_payouts_stripe_dashboard;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function data()
    {
        return [
            'redirect' => [
                'type' => 'string',
                'description' => 'Ссылка на страницу страйпа ЛК',
            ],
        ];
    }
}

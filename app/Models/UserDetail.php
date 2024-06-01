<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Environment;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $user_id
 * @property string|null $description
 * @property string|null $test_customer_id
 * @property string|null $customer_id
 * @property string|null $test_stripe_account
 * @property string|null $stripe_account
 * @property string|null $hostfully_agency_uid
 * @property int $hostfully_status
 *
 * @property string|null $customerValue
 * @property string|null $stripeAccountValue
 *
 * @property User $user
 */
class UserDetail extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_details';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'description',
        'test_customer_id', 'customer_id',
        'test_stripe_account', 'stripe_account',
        'hostfully_agency_uid', 'hostfully_status',
    ];

    /**
     * @return string|null
     */
    public function getCustomerValueAttribute()
    {
        if (config('app.env') === Environment::PRODUCTION) {
            return $this->customer_id;
        }
        return $this->test_customer_id;
    }

    /**
     * @return string|null
     */
    public function getStripeAccountValueAttribute()
    {
        if (config('app.env') === Environment::PRODUCTION) {
            return $this->stripe_account;
        }
        return $this->test_stripe_account;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

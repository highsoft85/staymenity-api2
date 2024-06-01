<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\PaymentStatusesTrait;
use App\Models\Traits\PayoutStatusesTrait;
use App\Services\Modelable\Statusable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payout
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $payment_id
 * @property float $amount Вся цена
 * @property string $provider
 * @property string|null $provider_payout_id
 * @property string|null $provider_transaction_id
 * @property string $provider_transfer_id
 * @property int $status
 * @property Carbon|null $created_at
 *
 *
 * @property User|null $user
 * @property User|null $userTrashed
 * @property Reservation|null $reservation
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\Payout::scopeActive()
 *
 * @method static cancelled()
 * @see \App\Models\Payout::scopeCancelled()
 *
 * @method static pending()
 * @see \App\Models\Payout::scopePending()
 *
 * @method static ordered()
 * @see \App\Models\Payout::scopeOrdered()
 *
 */
class Payout extends Model
{
    use Statusable;
    use PayoutStatusesTrait;

    /**
     * @var string
     */
    protected $table = 'payouts';

    /**
     * @var array
     */
    protected $fillable = [
        'provider', 'provider_payout_id', 'provider_transaction_id', 'provider_transfer_id',
        'user_id', 'payment_id', 'amount', 'status',
    ];

    const PROVIDER_STRIPE = 'stripe';

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_TRANSFER = 2;
    const STATUS_PENDING = 3;
    const STATUS_COMPLETED = 4;
    const STATUS_CANCELLED = 5;
    const STATUS_DEACTIVATED = 6;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return in_array($this->status, [
            self::STATUS_ACTIVE,
            self::STATUS_PENDING,
        ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_ACTIVE,
                self::STATUS_TRANSFER,
                self::STATUS_PENDING,
                self::STATUS_COMPLETED,
                self::STATUS_CANCELLED,
            ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_CANCELLED,
            ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_PENDING,
            ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function userTrashed()
    {
        return $this->user()->withTrashed();
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reservation()
    {
        return $this->hasOne(Reservation::class, 'payout_id');
    }
}

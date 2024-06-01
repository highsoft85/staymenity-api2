<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\PaymentStatusesTrait;
use App\Services\Modelable\Statusable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Payment
 * @package App\Models
 *
 * @property int $id
 * @property string $provider
 * @property string $provider_payment_id
 * @property int $user_from_id
 * @property int $user_to_id
 * @property float $amount Вся цена
 * @property float $service_fee Сколько в цене service_fee
 * @property int $status
 * @property Carbon|null $created_at
 *
 * @property Reservation|null $reservation
 * @property User|null $userFrom
 * @property User|null $userFromTrashed
 * @property User|null $userTo
 * @property User|null $userToTrashed
 * @property PaymentCharge[] $charges
 *
 * @property float $amountWithoutService
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\Payment::scopeActive()
 *
 * @method static activeNotCancelled()
 * @see \App\Models\Payment::scopeActiveNotCancelled()
 *
 * @method static cancelled()
 * @see \App\Models\Payment::scopeCancelled()
 *
 * @method static ordered()
 * @see \App\Models\Payment::scopeOrdered()
 *
 */
class Payment extends Model
{
    use Statusable;
    use PaymentStatusesTrait;

    /**
     * @var string
     */
    protected $table = 'payments';

    /**
     * @var array
     */
    protected $fillable = [
        'provider', 'provider_payment_id', 'user_from_id', 'user_to_id', 'amount', 'service_fee', 'status',
    ];

    const PROVIDER_STRIPE = 'stripe';


    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_CANCELLED = 2;
    const STATUS_WAIT_FOR_CAPTURE = 3;
    const STATUS_DEACTIVATED = 4;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|User
     */
    public function userFrom()
    {
        return $this->belongsTo(User::class, 'user_from_id');
    }

    /**
     * @return mixed
     */
    public function userFromTrashed()
    {
        return $this->userFrom()->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|User
     */
    public function userTo()
    {
        return $this->belongsTo(User::class, 'user_to_id');
    }

    /**
     * @return mixed
     */
    public function userToTrashed()
    {
        return $this->userTo()->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reservation()
    {
        return $this->hasOne(Reservation::class, 'payment_id');
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
                self::STATUS_CANCELLED,
                self::STATUS_WAIT_FOR_CAPTURE,
            ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActiveNotCancelled(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_ACTIVE,
                self::STATUS_WAIT_FOR_CAPTURE,
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
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * @return float
     */
    public function getAmountWithoutServiceAttribute()
    {
        $value = $this->amount - $this->service_fee;
        return (float)$value;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function charges()
    {
        return $this->hasMany(PaymentCharge::class, 'payment_id')->ordered();
    }
}

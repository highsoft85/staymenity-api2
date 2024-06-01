<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\PaymentStatusesTrait;
use App\Models\Traits\PayoutStatusesTrait;
use App\Models\Traits\TransferStatusesTrait;
use App\Services\Modelable\Statusable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Transfer
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property float $amount Вся цена
 * @property string $provider
 * @property string $provider_transfer_id
 * @property int $status
 *
 *
 * @property User|null $user
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\Transfer::scopeActive()
 *
 * @method static cancelled()
 * @see \App\Models\Transfer::scopeCancelled()
 *
 * @method static pending()
 * @see \App\Models\Transfer::scopePending()
 *
 * @method static ordered()
 * @see \App\Models\Transfer::scopeOrdered()
 *
 */
class Transfer extends Model
{
    use Statusable;
    use TransferStatusesTrait;

    /**
     * @var string
     */
    protected $table = 'transfers';

    /**
     * @var array
     */
    protected $fillable = [
        'provider', 'provider_transfer_id',
        'user_id', 'amount', 'status',
    ];

    const PROVIDER_STRIPE = 'stripe';

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;
    const STATUS_PENDING = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELLED = 4;
    const STATUS_DEACTIVATED = 5;

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function reservation()
    {
        return $this->hasOne(Reservation::class, 'transfer_id');
    }
}

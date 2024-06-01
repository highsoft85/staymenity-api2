<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentCharge
 * @package App\Models
 *
 * @property int $id
 * @property int $payment_id
 * @property string $type
 * @property float $amount
 * @property int $status
 *
 * * * METHODS
 * @method static cancellation()
 * @see \App\Models\PaymentCharge::scopeCancellation()
 *
 * @method static active()
 * @see \App\Models\PaymentCharge::scopeActive()
 *
 * @method static ordered()
 * @see \App\Models\PaymentCharge::scopeOrdered()
 *
 */
class PaymentCharge extends Model
{
    /**
     * @var string
     */
    protected $table = 'payment_charges';

    /**
     * @var array
     */
    protected $fillable = [
        'payment_id', 'type', 'amount', 'status',
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const TYPE_CANCELLATION = 'cancellation';

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_ACTIVE);
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
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }


    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeCancellation(Builder $query): Builder
    {
        return $query
            ->where('type', self::TYPE_CANCELLATION);
    }
}

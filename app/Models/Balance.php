<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Balance
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property float $amount
 * @property int $status
 *
 * @property User|null $user
 * @property User|null $userTrashed
 *
 * @deprecated
 */
class Balance extends Model
{
    /**
     * @var string
     */
    protected $table = 'balances';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id', 'amount', 'status',
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

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
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }
}

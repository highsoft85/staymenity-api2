<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Statusable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Review
 * @package App\Models
 *
 * @property int $id
 * @property User $user
 * @property int $user_id
 * @property int|null $reservation_id
 * @property int $reviewable_id
 * @property float|int|null $rating
 * @property string $reviewable_type
 * @property string|null $description
 * @property Carbon|null $published_at
 * @property Carbon|null $banned_at
 * @property int $status
 *
 *
 * @property Reservation|null $reservation
 * @property User|Listing|null $model
 *
 *
 * @property User|null $userTrashed
 * @property User|Listing|null $modelTrashed
 */
class Review extends Model
{
    use Statusable;

    /**
     * @var string
     */
    protected $table = 'reviews';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'reservation_id', 'reviewable_id', 'reviewable_type', 'description', 'rating', 'published_at',
        'banned_at', 'status',
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @var array
     */
    protected $dates = [
        'published_at', 'banned_at',
    ];

    /**
     * @return bool
     */
    public function isActive()
    {
        return in_array($this->status, [
            self::STATUS_ACTIVE,
        ]) && is_null($this->banned_at);
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
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo|User|Listing
     */
    public function model()
    {
        return $this->morphTo('reviewable');
    }

    /**
     * @return mixed
     */
    public function modelTrashed()
    {
        // по model() ищет
        // @phpstan-ignore-next-line
        return $this->morphTo('reviewable')->withTrashed();
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->whereHas('user', function (Builder $q) {
                $q->whereNull('deleted_at');
            })
            ->whereNotNull('published_at')
            ->whereNull('banned_at')
            ->where('status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('published_at', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}

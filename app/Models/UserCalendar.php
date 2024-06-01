<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Statusable;
use App\Services\Modelable\Typeable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class UserCalendar
 * @package App\Models
 *
 * @property string $type
 * @property int $user_id
 * @property int $listing_id
 * @property int|null $reservation_id
 * @property int|null $hostfully_reservation_id
 * @property Carbon $date_at
 * @property int $bookend_count
 * @property int $is_weekend
 * @property int $status
 *
 *
 * @property Listing|null $listing
 * @property Reservation|null $reservation
 * @property HostfullyReservation|null $hostfully
 *
 * * * METHODS
 * @method static locked()
 * @see \App\Models\UserCalendar::scopeLocked()
 *
 * @method static booked()
 * @see \App\Models\UserCalendar::scopeBooked()
 *
 * @method static bookedFull()
 * @see \App\Models\UserCalendar::scopeBookedFull()
 *
 * @method static weekends()
 * @see \App\Models\UserCalendar::scopeWeekends()
 *
 * @method static weekdays()
 * @see \App\Models\UserCalendar::scopeWeekdays()
 *
 * @method static active()
 * @see \App\Models\UserCalendar::scopeActive()
 *
 * @method static ordered()
 * @see \App\Models\UserCalendar::scopeOrdered()
 *
 * @method static byUser($oUser)
 * @see \App\Models\UserCalendar::scopeByUser()
 *
 * @method static notInSearch()
 * @see \App\Models\UserCalendar::scopeNotInSearch()
 *
 */
class UserCalendar extends Model
{
    use Typeable;
    use Statusable;

    /**
     * @var string
     */
    protected $table = 'user_calendars';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'user_id', 'listing_id', 'reservation_id', 'hostfully_reservation_id',
        'date_at', 'bookend_count',
        'is_weekend', 'status',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $dates = [
        'date_at',
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    //const TYPE_DEFAULT = 'default';
    const TYPE_AVAILABLE = 'available';
    const TYPE_BOOKED = 'booked';
    const TYPE_BOOKED_FULL = 'booked_full';
    const TYPE_LOCKED = 'locked';
    const TYPE_DISABLED = 'disabled';

    const ACTION_UNLOCK_ALL = 'unlock_all';
    const ACTION_UNLOCK_WEEKDAYS = 'unlock_weekdays';
    const ACTION_UNLOCK_WEEKENDS = 'unlock_weekends';

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
    public function scopeLocked(Builder $query): Builder
    {
        return $query
            ->where('type', self::TYPE_LOCKED);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeBooked(Builder $query): Builder
    {
        return $query
            ->whereIn('type', [
                self::TYPE_BOOKED,
                self::TYPE_BOOKED_FULL,
            ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWeekends(Builder $query): Builder
    {
        return $query
            ->where('is_weekend', 1);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotInSearch(Builder $query): Builder
    {
        return $query
            ->whereIn('type', [
                //self::TYPE_BOOKED,
                self::TYPE_BOOKED_FULL, // когда весь день забронировали, например через hostfully
                self::TYPE_LOCKED,
            ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWeekdays(Builder $query): Builder
    {
        return $query
            ->where('is_weekend', 0);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('date_at');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * @param Builder $query
     * @param User $oUser
     * @return Builder
     */
    public function scopeByUser(Builder $query, User $oUser)
    {
        return $query
            ->where('user_id', $oUser->id);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hostfully()
    {
        return $this->belongsTo(HostfullyReservation::class, 'hostfully_reservation_id');
    }
}

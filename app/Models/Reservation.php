<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\ReservationStatusesTrait;
use App\Services\Model\ReservationServiceModel;
use App\Services\Modelable\Statusable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class Reservations
 * @package App\Models
 *
 * @property int $id
 * @property int|null $user_id
 * @property int|null $listing_id
 * @property int|null $payment_id
 * @property int|null $payout_id
 * @property int|null $transfer_id
 * @property string|null $message
 * @property Carbon $start_at
 * @property Carbon $server_start_at
 * @property Carbon $finish_at
 * @property Carbon $server_finish_at
 * @property Carbon $free_cancellation_at
 * @property Carbon|null $cancelled_at
 * @property Carbon|null $declined_at
 * @property Carbon|null $accepted_at
 * @property Carbon|null $beginning_at
 * @property Carbon|null $passed_at
 * @property Carbon|null $transfer_at
 * @property Carbon|null $payout_at
 * @property float $total_price
 * @property float $price
 * @property float $service_fee
 * @property int $is_agree
 * @property int|null $guests_size
 * @property string $code
 * @property int $status
 * @property int $sync_hostfully
 * @property string|null $cancelled_type
 * @property string $source
 * @property string|null $timezone
 * @property Carbon $created_at
 *
 *
 * @property string $reservationTime
 * @see \App\Models\Reservation::getReservationTimeAttribute()
 *
 * @property string $reservationTimeFormat
 * @see \App\Models\Reservation::getReservationTimeFormatAttribute()
 *
 * @property string $transferDescription
 * @see \App\Models\Reservation::getTransferDescriptionAttribute()
 *
 * @property string $paymentDescription
 * @see \App\Models\Reservation::getPaymentDescriptionAttribute()
 *
 * @property string $paymentDescriptionDate
 * @see \App\Models\Reservation::getPaymentDescriptionDateAttribute()
 *
 * @property string $fullCode
 * @see \App\Models\Reservation::getFullCodeAttribute()
 *
 * @property string $hours
 * @see \App\Models\Reservation::getHoursAttribute()
 *
 * @property string|null $cancelledTypeText
 * @see \App\Models\Reservation::getCancelledTypeTextAttribute()
 *
 * * * RELATIONSHIPS
 * @property User|null $user
 * @property User|null $userTrashed
 * @property Listing|null $listing
 * @property Listing|null $listingTrashed
 * @property Payment|null $payment
 * @property Payout|null $payout
 * @property Transfer|null $transfer
 * @property UserCalendar[] $userCalendar
 * @property HostfullyReservation|null $hostfully
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\Reservation::scopeActive()
 *
 * @method static beginning()
 * @see \App\Models\Reservation::scopeBeginning()
 *
 * @method static activeCheckLocked()
 * @see \App\Models\Reservation::scopeActiveCheckLocked()
 *
 * @method static future()
 * @see \App\Models\Reservation::scopeFuture()
 *
 * @method static futureNotBeginning()
 * @see \App\Models\Reservation::scopeFutureNotBeginning()
 *
 * @method static futureNotBeginningAll()
 * @see \App\Models\Reservation::scopeFutureNotBeginningAll()
 *
 * @method static futureNotPassed()
 * @see \App\Models\Reservation::scopeFutureNotPassed()
 *
 * @method static cancelled()
 * @see \App\Models\Reservation::scopeCancelled()
 *
 * @method static cancelledOrDeclined()
 * @see \App\Models\Reservation::scopeCancelledOrDeclined()
 *
 * @method static cancelledOrDeclinedOrNotActive()
 * @see \App\Models\Reservation::scopeCancelledOrDeclinedOrNotActive()
 *
 * @method static withPayouts()
 * @see \App\Models\Reservation::scopeWithPayouts()
 *
 * @method static passed()
 * @see \App\Models\Reservation::scopePassed()
 *
 * @method static ordered()
 * @see \App\Models\Reservation::scopeOrdered()
 *
 */
class Reservation extends Model
{
    use Statusable;
    use ReservationStatusesTrait;

    /**
     * @var string
     */
    protected $table = 'reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'listing_id', 'payment_id', 'transfer_id', 'payout_id',
        'message', 'source',
        'server_start_at', 'server_finish_at',
        'start_at', 'finish_at', 'free_cancellation_at',
        'cancelled_at', 'declined_at', 'accepted_at', 'beginning_at', 'passed_at', 'transfer_at', 'payout_at',
        'total_price', 'price', 'service_fee',
        'is_agree', 'guests_size',
        'code', 'cancelled_type', 'sync_hostfully',
        'status', 'timezone',
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_DRAFT = 1;

    // ожидает оплаты
    const STATUS_PENDING = 2;

    // оплачен
    const STATUS_ACCEPTED = 3;
    const STATUS_DECLINED = 4;
    const STATUS_CANCELLED = 5;

    const SEARCH_TYPE_UPCOMING = 'upcoming';
    const SEARCH_TYPE_PREVIOUS = 'previous';
    const SEARCH_TYPE_CANCELLED = 'cancelled';

    const SERVICE_FEE = 35;
    const FREE_CANCELLATION = 48;
    const CANCELLATION_CHARGE = 50;
    const PAYMENT_TIMEOUT = 5;

    const CANCELLED_TYPE_BY_USER_DELETED = 'user_deleted';
    const CANCELLED_TYPE_BY_LISTING_DELETED = 'listing_deleted';
    const CANCELLED_TYPE_BY_GUEST = 'guest';
    const CANCELLED_TYPE_BY_HOST = 'host';
    const CANCELLED_TYPE_BY_ADMIN = 'admin';

    const SOURCE_APP = 'app';
    const SOURCE_HOSTFULLY = 'hostfully';

    const SYNC_HOSTFULLY_NOT_ACTIVE = 0;
    const SYNC_HOSTFULLY_ACTIVE = 1;

    /**
     * @var array
     */
    protected $dates = [
        'server_start_at',
        'server_finish_at',
        'start_at', 'finish_at',
        'free_cancellation_at',
        'cancelled_at',
        'declined_at',
        'accepted_at',
        'beginning_at',
        'passed_at',
        'transfer_at',
        'payout_at',
    ];

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query)
    {
        return $query
            ->where('status', self::STATUS_ACCEPTED);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActiveCheckLocked(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_DRAFT,
                self::STATUS_PENDING,
                self::STATUS_ACCEPTED,
            ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('start_at');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderedForPassed(Builder $query): Builder
    {
        return $query->orderBy('start_at', 'desc');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopePassed(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_ACCEPTED)
            ->where('server_finish_at', '<', now())
            ->whereNotNull('passed_at');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeBeginning(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_ACCEPTED)
            ->whereNotNull('beginning_at');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWithPayouts(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_ACCEPTED)
            ->whereNotNull('payout_id')
            ->whereNotNull('payout_at');
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
            ])
            ->whereNotNull('cancelled_at');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeCancelledOrDeclined(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_CANCELLED,
                self::STATUS_DECLINED,
            ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeCancelledOrDeclinedOrNotActive(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_NOT_ACTIVE,
                self::STATUS_CANCELLED,
                self::STATUS_DECLINED,
            ]);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeFuture(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_ACCEPTED,
            ])
            ->where('server_finish_at', '>', now());
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeFutureNotPassed(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_ACCEPTED,
            ])
            ->where('server_finish_at', '>', now());
    }

    /**
     * Будущие брони
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFutureNotBeginning(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_ACCEPTED,
            ])
            ->where('server_start_at', '>', now());
    }


    /**
     * Будущие брони
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeFutureNotBeginningAll(Builder $query): Builder
    {
        return $query
            ->whereIn('status', [
                self::STATUS_PENDING,
                self::STATUS_ACCEPTED,
                self::STATUS_DECLINED,
                self::STATUS_CANCELLED,
            ])
            ->where('server_start_at', '>', now());
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Listing
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * @return mixed
     */
    public function listingTrashed()
    {
        return $this->listing()->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function payout()
    {
        return $this->belongsTo(Payout::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function transfer()
    {
        return $this->belongsTo(Transfer::class);
    }

    /**
     * @return bool
     */
    public function isActive()
    {
        return in_array($this->status, [
            self::STATUS_DRAFT,
            self::STATUS_PENDING,
            self::STATUS_ACCEPTED,
        ]);
    }

    /**
     * @return bool
     */
    public function isFreeCancellation()
    {
        return now()->isBefore($this->free_cancellation_at);
    }

    /**
     * @return bool
     */
    public function isDeclined()
    {
        return !is_null($this->declined_at);
    }

    /**
     * @return bool
     */
    public function isCancelled()
    {
        return !is_null($this->cancelled_at);
    }

    /**
     * @return bool
     */
    public function isBeginning()
    {
        if (!is_null($this->beginning_at)) {
            return true;
        }
        if (now()->between($this->server_start_at, $this->server_finish_at)) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function isPassed()
    {
        if (!is_null($this->passed_at)) {
            return true;
        }
        if (now() > $this->server_finish_at) {
            return true;
        }
        return false;
    }

    /**
     * @return bool
     */
    public function typeIsUpcoming()
    {
        return $this->status === self::STATUS_ACCEPTED && $this->start_at >= now();
    }

    /**
     * @return bool
     */
    public function typeIsPrevious()
    {
        return $this->status === self::STATUS_ACCEPTED && $this->finish_at <= now();
    }

    /**
     * @return bool
     */
    public function typeIsCancelled()
    {
        return $this->isDeclined() || $this->isCancelled();
    }

    /**
     * @return array
     */
    public function getType()
    {
        if ($this->typeIsCancelled()) {
            return [
                'name' => self::SEARCH_TYPE_CANCELLED,
                'title' => 'Cancelled',
            ];
        }
        if ($this->typeIsPrevious()) {
            return [
                'name' => self::SEARCH_TYPE_PREVIOUS,
                'title' => 'Completed',
            ];
        } else {
            return [
                'name' => self::SEARCH_TYPE_UPCOMING,
                'title' => 'Upcoming',
            ];
        }
    }

    /**
     * @return string
     */
    public function getReservationTimeAttribute()
    {
        $startHour = (int)$this->start_at->format('h');
        $startA = mb_strtolower($this->start_at->format('A'));
        $startText = $startHour . ' ' . $startA;

        $finishHour = (int)$this->finish_at->copy()->addMinute()->format('h');
        $finishA = mb_strtolower($this->finish_at->copy()->addMinute()->format('A'));
        $finishText = $finishHour . ' ' . $finishA;

        return $startText . ' - ' . $finishText;
    }

    /**
     * @return string
     */
    public function getReservationTimeFormatAttribute()
    {
        $startHour = $this->start_at->format('H:i');
        $startText = $startHour;

        $finishHour = $this->finish_at->copy()->addMinute()->format('H:i');
        $finishText = $finishHour;

        return $startText . ' - ' . $finishText;
    }

    /**
     * @return string
     */
    public function getPaymentDescriptionAttribute()
    {
        $text = '';
        $text .= 'R';
        $text .= $this->id;
        $text .= Str::upper(mb_substr(config('app.env'), 0, 1)); // P, T, D, L
        $text .= $this->code;
        return $text;
    }

    /**
     * @return string
     */
    public function getFullCodeAttribute()
    {
        $text = '';
        $text .= 'R';
        $text .= $this->id;
        $text .= Str::upper(mb_substr(config('app.env'), 0, 1)); // P, T, D, L
        $text .= $this->code;
        return $text;
    }

    /**
     * @return string
     */
    public function getTransferDescriptionAttribute()
    {
        return $this->paymentDescription . ' ' . $this->listing->title . ' - ' . $this->paymentDescriptionDate;
    }

    /**
     * @return string
     */
    public function getPaymentDescriptionDateAttribute()
    {
        $text = '';
        $date = $this->start_at->format('m-d-Y');
        $text .= $date . ' (' . $this->reservationTime . ')';
        return $text;
    }

    /**
     * @return int
     */
    public function getHoursAttribute()
    {
        $startAt = $this->start_at->copy();
        $finisHAt = $this->finish_at->copy()->addMinute();
        return $startAt->diffInHours($finisHAt);
    }

    /**
     * @return string|null
     */
    public function getCancelledTypeTextAttribute()
    {
        return (new ReservationServiceModel($this))->getCancelledTypeText();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reviews()
    {
        return $this->hasMany(Review::class, 'reservation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserCalendar
     */
    public function userCalendar()
    {
        return $this->hasMany(UserCalendar::class, 'reservation_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|HostfullyReservation
     */
    public function hostfully()
    {
        return $this->hasOne(HostfullyReservation::class, 'reservation_id', 'id');
    }

    /**
     * @return bool
     */
    public function fromHostfully(): bool
    {
        return $this->source === self::SOURCE_HOSTFULLY;
    }

    /**
     * @return bool
     */
    public function fromApp(): bool
    {
        return $this->source === self::SOURCE_APP;
    }

    /**
     *
     */
    public function syncHostfullySetActive()
    {
        if ($this->sync_hostfully !== self::SYNC_HOSTFULLY_ACTIVE) {
            $this->update([
                'sync_hostfully' => self::SYNC_HOSTFULLY_ACTIVE,
            ]);
        }
    }

    /**
     * @return bool
     */
    public function isByDays()
    {
        return $this->daysCount() > 0;
    }

    /**
     * @return int
     */
    public function daysCount()
    {
        return $this->start_at->diffInDays($this->finish_at->copy()->addSecond());
    }

//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasOne|Chat
//     */
//    public function chat()
//    {
//        return $this->hasOne(Chat::class, 'reservation_id');
//    }
//
//    /**
//     * @return \Illuminate\Database\Eloquent\Relations\HasOne|Chat
//     */
//    public function chatActive()
//    {
//        return $this->chat()->active();
//    }
}

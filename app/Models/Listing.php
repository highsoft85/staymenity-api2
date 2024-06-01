<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\ListingStatusesTrait;
use App\Services\Model\ListingServiceModel;
use App\Services\Modelable\Imageable;
use App\Services\Modelable\Locationable;
use App\Services\Modelable\Ratingable;
use App\Services\Modelable\Reviewable;
use App\Services\Modelable\Statusable;
use App\Services\Modelable\Visitable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class Listing
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $creator_id
 * @property string $name
 * @property string $title
 * @property int $type_id
 * @property float|null $price
 * @property float|null $price_per_day
 * @property int|null $cleaning_fee
 * @property int|null $deposit
 * @property string $description
 * @property int|null $guests_size
 * @property int $rent_time_min
 * @property int $is_free_service
 * @property Carbon|null $banned_at
 * @property Carbon|null $published_at
 * @property float $run_rating
 * @property int $status
 * @property string|null $timezone
 * @property Carbon|null $created_at
 *
 * @property mixed $values
 * @property Type|null $type
 * @property ListingSetting|null $settings
 *
 * @property Amenity[] $amenities
 * @property Rule[] $rules
 * @property ListingTime[] $times
 * @property UserCalendar[] $calendarDates
 *
 *
 * @property string $slug
 * @property array $currentStatus
 *
 * * * RELATIONSHIPS
 * @property User|null $user
 * @property User|null $userTrashed
 * @property User|null $creator
 * @property Reservation[] $reservations
 * @property Reservation[] $reservationsActive
 * @property Reservation[] $reservationsPassed
 * @property HostfullyListing|null $hostfully
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\Listing::scopeActive()
 *
 * @method static activeForHost()
 * @see \App\Models\Listing::scopeActiveForHost()
 *
 * @method static ordered()
 * @see \App\Models\Listing::scopeOrdered()
 *
 * @method static orderedBySearch()
 * @see \App\Models\Listing::scopeOrderedBySearch()
 *
 * @package App
 */
class Listing extends Model
{
    use SoftDeletes;
    use Statusable;
    use Imageable;
    use Locationable;
    use ListingStatusesTrait;
    use Reviewable;
    use Ratingable;
    use Visitable;

    /**
     * Статусы активности
     */
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    const GUEST_SIZE_TYPE_ONE_TO_FIVE = 'one_to_five';
    const GUEST_SIZE_TYPE_SIX_TO_FIFTEEN = 'six_to_fifteen';
    const GUEST_SIZE_TYPE_MORE_THAN_FIFTEEN = 'more_than_fifteen';
    const GUEST_SIZE_TYPE_CUSTOM = 'custom';

    const STATUS_NAME_FREE = 'free';
    const STATUS_NAME_BANNED = 'banned';
    const STATUS_NAME_BOOKED = 'booked';
    const STATUS_NAME_UNAVAILABLE = 'unavailable';
    const STATUS_NAME_ON_REVIEW = 'on_review';
    const STATUS_NAME_ON_PENDING = 'on_pending';
    const STATUS_NAME_DRAFT = 'draft';
    const STATUS_NAME_UNLISTED = 'unlisted';

    /**
     * @var string
     */
    protected $table = 'listings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'creator_id', 'owner_id',
        'name', 'title', 'type_id', 'description', 'price', 'deposit', 'cleaning_fee', 'price_per_day',
        'guests_size', 'rent_time_min', 'is_free_service', 'run_rating',
        'banned_at', 'published_at',
        'status', 'timezone',
    ];

    /**
     * @var string[]
     */
    protected $dates = [
        'banned_at', 'published_at',
    ];

    /**
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isDraft(): bool
    {
        return is_null($this->published_at) && $this->status === self::STATUS_NOT_ACTIVE;
        //return $this->status === self::STATUS_NOT_ACTIVE;
    }

    /**
     * @return bool
     */
    public function isBanned(): bool
    {
        return !is_null($this->banned_at);
    }

    /**
     *
     */
    public function getUrl()
    {
        return config('app.web_url') . '/' . $this->type->name . '/' . $this->slug;
    }

    /**
     * @return string
     */
    public function getSlugAttribute()
    {
        return $this->name . '-' . $this->id;
    }

    /**
     * @param string $value
     */
    public function setTitleAttribute(string $value)
    {
        $this->attributes['title'] = $value;
        $this->attributes['name'] = Str::slug($value);
    }

    /**
     * @param $value
     */
    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = $value;
        $this->attributes['price_per_day'] = $value * 24;
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query
            ->where('status', self::STATUS_ACTIVE)
            ->whereNotNull('published_at')
            ->whereNull('banned_at');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActiveForHost(Builder $query): Builder
    {
        return $query
            ->whereNull('banned_at');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('created_at');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderedBySearch(Builder $query): Builder
    {
        return $query->orderBy('run_rating', 'desc');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function type()
    {
        return $this->belongsTo(Type::class)->withTrashed();
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Rule
     */
    public function rules()
    {
        return $this->belongsToMany(Rule::class, 'listing_rule');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Builder
     */
    public function rulesActive()
    {
        return $this->rules()->active()->ordered();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Amenity
     */
    public function amenities()
    {
        return $this->belongsToMany(Amenity::class, 'listing_amenity');
    }

    /**
     * @return mixed
     */
    public function amenitiesActive()
    {
        return $this->amenities()->active()->ordered();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne(ListingSetting::class, 'listing_id');
    }

    /**
     * @param User $oUser
     * @return bool
     */
    public function hasAccess(User $oUser)
    {
        return $this->user_id === $oUser->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|ListingTime
     */
    public function times()
    {
        return $this->hasMany(ListingTime::class, 'listing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserCalendar
     */
    public function calendarDates()
    {
        return $this->hasMany(UserCalendar::class, 'listing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserCalendar|Builder
     */
    public function calendarDatesActive()
    {
        return $this->calendarDates()->active();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserCalendar|Builder
     */
    public function calendarDatesLocked()
    {
        return $this->calendarDates()->locked();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserCalendar|Builder
     */
    public function calendarDatesActionNotInSearch()
    {
        return $this->calendarDatesActive()->notInSearch();
    }

    /**
     * @param User $oUser
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|UserCalendar
     */
    public function calendarDatesByUser(User $oUser)
    {
        return $this->calendarDates()->where('user_id', $oUser->id);
    }

    /**
     * @return array
     */
    public function getCurrentStatusAttribute()
    {
        return (new ListingServiceModel($this))->getStatus();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Reservation
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'listing_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Reservation
     */
    public function reservationsActive()
    {
        return $this->reservations()->active();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Reservation
     */
    public function reservationsPassed()
    {
        return $this->reservations()->passed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Reservation
     */
    public function reservationsFuture()
    {
        return $this->reservations()->future();
    }

    /**
     * @return string
     */
    public function getSearchNameAttribute()
    {
        return '#' . $this->id . ' ' . $this->title . ' (' . $this->userTrashed->email . ')';
    }

    /**
     * @return bool
     */
    public function isFreeService()
    {
        return $this->is_free_service === 1;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|HostfullyListing
     */
    public function hostfully()
    {
        return $this->hasOne(HostfullyListing::class, 'listing_id', 'id');
    }
}

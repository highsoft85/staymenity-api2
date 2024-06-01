<?php

declare(strict_types=1);

namespace App\Models;

use App\Events\Auth\ResetPasswordEvent;
use App\Models\Traits\CanReceivePushTrait;
use App\Models\Traits\CanReceiveSlackNotificationTrait;
use App\Models\Traits\UserBirthdayAtTrait;
use App\Models\Traits\UserNotificationsTrait;
use App\Models\Traits\UserReviewsTrait;
use App\Models\Traits\UserRolesTrait;
use App\Models\Traits\UserStatusesTrait;
use App\Services\Modelable\Imageable;
use App\Services\Modelable\Locationable;
use App\Services\Modelable\Ratingable;
use App\Services\Modelable\Reviewable;
use App\Services\Modelable\Socialable;
use App\Services\Modelable\Statusable;
use App\Services\Modelable\UserFavoriteable;
use App\Services\Modelable\Visitable;
use Carbon\Carbon;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Tags\HasTags;
use Illuminate\Notifications\Notification;

/**
 * Class User
 * @package App\Models
 *
 * @property int $id
 * @property string|null $email
 * @property string $first_name
 * @property string $last_name
 * @property string|null $phone
 * @property string $login
 * @property int|null $gender
 * @property string|null $register_by
 * @property string|null $current_role
 * @property Carbon|null $birthday_at
 * @property Carbon|null $email_verified_at
 * @property Carbon|null $phone_verified_at
 * @property Carbon|null $identity_verified_at
 * @property Carbon|null $registered_at
 * @property Carbon|null $last_login_at
 * @property Carbon|null $banned_at
 * @property string|null $timezone
 * @property int $is_has_password
 * @property int $has_payout_connect
 * @property string $source
 * @property int $status
 *
 * @property Carbon|null $created_at
 *
 * @property Reservation[] $reservations
 * @property Reservation[] $reservationsActive
 * @property Listing[] $listings
 * @property Listing[] $listingsActive
 * @property UserSocialAccount|null $socialAccount
 * @property UserSocialAccount[]|Collection $socialAccounts
 * @property UserDetail|null $details
 * @property UserSetting|null $settings
 * @property UserCalendar[] $calendarDates
 * @property Balance|null $balance
 * @property Chat[] $chatsActive
 * @property ChatMessage[] $chatsMessagesActive
 *
 *
 * * * ACCESSORS (только camelCase, чтобы удобнее различать)
 * @property string $emailToken
 * @property string $searchName
 * @property string $fullName
 * @property string $phoneFormatted
 * @property HostfullyUser|null $hostfully
 *
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\User::scopeActive()
 *
 * @method static ordered()
 * @see \App\Models\User::scopeOrdered()
 *
 */
class User extends Authenticatable
{
    use SoftDeletes;
    use Notifiable;
    use CanResetPassword;
    use HasRoles;
    use Statusable;
    use UserStatusesTrait;
    use HasApiTokens;
    use UserRolesTrait;
    use Socialable;
    use UserNotificationsTrait;
    use UserReviewsTrait;
    use Imageable;
    //use Rate;
    use Locationable;
    use Reviewable;
    use UserFavoriteable;
    use UserBirthdayAtTrait;
    use Ratingable;
    use Visitable;
    use CanReceivePushTrait;
    use CanReceiveSlackNotificationTrait;

    /**
     * Роли
     */
    const ROLE_DEVELOPER = 'developer';
    const ROLE_ADMIN = 'admin';
    const ROLE_MANAGER = 'manager';
    const ROLE_OWNER = 'owner';
    const ROLE_HOST = 'host';
    const ROLE_GUEST = 'guest';

    const REGISTER_BY_EMAIL = 'email';
    const REGISTER_BY_PHONE = 'phone';
    const REGISTER_BY_SOCIAL = 'social';
    const REGISTER_BY_RESERVATION = 'reservation';
    const REGISTER_BY_HOSTFULLY = 'hostfully';

    /**
     * Названия токеном
     */
    const TOKEN_AUTH_NAME = 'token-auth';
    const TOKEN_AUTH_DOCUMENTATION_NAME = 'token-documentation-auth';
    const TOKEN_AUTH_DEV_NAME = 'token-dev-auth';
    const TOKEN_AUTH_ADMIN_NAME = 'token-admin-auth';

    /**
     * Статусы активности
     */
    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     *
     */
    const GENDER_NOT_TO_SAY = 0;
    const GENDER_MALE = 1;
    const GENDER_FEMALE = 2;

    const SOURCE_APP = 'app';
    const SOURCE_HOSTFULLY = 'hostfully';

    /**
     * @var array
     */
    protected $genders = [
        self::GENDER_NOT_TO_SAY => 'Not to Say',
        self::GENDER_MALE => 'Male',
        self::GENDER_FEMALE => 'Female',
    ];

    /**
     * @param int $value
     * @return string
     */
    public function getGender(int $value): string
    {
        return $this->genders[$value];
    }

    /**
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'login', 'password', 'register_by', 'phone', 'gender',
        'email_verified_at',
        'phone_verified_at',
        'identity_verified_at',
        'registered_at', 'last_login_at',
        'current_role',
        'is_has_password',
        'has_payout_connect',
        'birthday_at', 'age', 'banned_at',
        'device_token',
        'status', 'source',
        'timezone',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $dates = [
        'email_verified_at', 'phone_verified_at', 'registered_at', 'banned_at', 'identity_verified_at',
        'birthday_at', 'last_login_at',
    ];

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
            ->where('status', self::STATUS_ACTIVE)
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
     *
     */
    public function getHostUrl()
    {
        return config('app.web_url') . '/host/' . $this->id;
    }

    /**
     *
     */
    public function getGuestUrl()
    {
        return config('app.web_url') . '/guest/' . $this->id;
    }

    /**
     * @return bool
     */
    public function isBanned()
    {
        return !is_null($this->banned_at);
    }

    /**
     * @return bool
     */
    public function isHasPassword()
    {
        return $this->is_has_password === 1;
    }

    /**
     * @return bool
     */
    public function isPhoneVerified()
    {
        return !is_null($this->phone_verified_at);
    }

    /**
     * @return bool
     */
    public function isIdentityVerified()
    {
        return !is_null($this->identity_verified_at);
    }

    /**
     * @return bool
     */
    public function isEmailVerified()
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * @return string
     */
    public function getEmailTokenAttribute()
    {
        return Crypt::encrypt($this->email);
    }

    /**
     * @param null|string $value
     */
    public function setPhoneAttribute(?string $value)
    {
        if (!is_null($value)) {
            $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
        } else {
            $this->attributes['phone'] = null;
        }
    }

    /**
     * @return string
     */
    public function getSearchNameAttribute()
    {
        return '#' . $this->id . ' ' . $this->first_name . ' ' . $this->last_name . ' (' . $this->email . ')';
    }

    /**
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Send the password reset notification.
     *
     * @param string $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        event(new ResetPasswordEvent($this, $token));
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function socialAccount()
    {
        return $this->hasOne(UserSocialAccount::class, 'user_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialAccounts()
    {
        return $this->hasMany(UserSocialAccount::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Listing
     */
    public function listings()
    {
        return $this->hasMany(Listing::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Builder|Listing
     */
    public function listingsActive()
    {
        return $this->listings()->active()->ordered();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Listing
     */
    public function saves()
    {
        return $this->hasMany(UserSave::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Builder|Listing
     */
    public function savesActive()
    {
        return $this->saves()->active()->ordered();
    }

    /**
     * @param Listing $oListing
     * @return bool
     */
    public function checkListingAccess(Listing $oListing)
    {
        return $oListing->user_id === $this->id;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function details()
    {
        return $this->hasOne(UserDetail::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function settings()
    {
        return $this->hasOne(UserSetting::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function calendarDates()
    {
        return $this->hasMany(UserCalendar::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Reservation
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Reservation
     */
    public function reservationsActive()
    {
        return $this->reservations()->active();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Payment
     */
    public function payouts()
    {
        return $this->hasMany(Payout::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Payment
     */
    public function paymentsToMe()
    {
        return $this->hasMany(Payment::class, 'user_to_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Payment
     */
    public function paymentsFromMe()
    {
        return $this->hasMany(Payment::class, 'user_from_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function balance()
    {
        return $this->hasOne(Balance::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function firebaseNotifications()
    {
        return $this->morphMany(FirebaseNotification::class, 'notifiable');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function devices()
    {
        return $this->morphMany(Device::class, 'model');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Chat
     */
    public function chats()
    {
        return $this->belongsToMany(Chat::class, 'user_chat');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|Chat
     */
    public function chatsActive()
    {
        return $this->chats()->active();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|ChatMessage
     */
    public function chatMessages()
    {
        return $this->hasMany(ChatMessage::class, 'user_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany|ChatMessage
     */
    public function chatMessagesActive()
    {
        return $this->chatMessages()->active();
    }

    /**
     * @return bool
     */
    public function isHost()
    {
        return $this->current_role === self::ROLE_HOST;
    }

    /**
     * @return bool
     */
    public function isGuest()
    {
        return $this->current_role === self::ROLE_GUEST;
    }

    /**
     * @return bool
     */
    public function hasPayoutConnect()
    {
        return $this->has_payout_connect === 1 && !is_null($this->details->stripeAccountValue);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function identities()
    {
        return $this->hasMany(UserIdentity::class, 'user_id');
    }
//
//    /**
//     * @return Carbon|null
//     */
//    public function getLastLoginAtAttribute()
//    {
//        $tokens = $this->tokens()->orderBy('last_used_at', 'desc')->get();
//        if (empty($tokens)) {
//            return null;
//        }
//        $token = $tokens->first();
//        if (is_null($token->last_used_at)) {
//            return null;
//        }
//        return Carbon::parse($token->last_used_at);
//    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne|HostfullyUser
     */
    public function hostfully()
    {
        return $this->hasOne(HostfullyUser::class, 'user_id', 'id');
    }
}

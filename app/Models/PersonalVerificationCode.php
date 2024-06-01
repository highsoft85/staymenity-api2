<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class PersonalVerificationCode
 * @package App\Models
 *
 * @property int $id
 * @property string $type
 * @property string $phone
 * @property int $user_id
 * @property string $code
 * @property Carbon|null $expires_at
 * @property Carbon|null $verified_at
 * @property Carbon|null $created_at
 *
 * @property User|null $user
 *
 * * * METHODS
 * @method static registration()
 * @see \App\Models\PersonalVerificationCode::scopeRegistration()
 *
 * @method static login()
 * @see \App\Models\PersonalVerificationCode::scopeLogin()
 *
 * @method static change()
 * @see \App\Models\PersonalVerificationCode::scopeChange()
 *
 * @method static reservation()
 * @see \App\Models\PersonalVerificationCode::scopeReservation()
 *
 * @method static reset()
 * @see \App\Models\PersonalVerificationCode::scopeReset()
 *
 */
class PersonalVerificationCode extends Model
{
    /**
     * @var string
     */
    protected $table = 'personal_verification_codes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'phone', 'user_id', 'code', 'expires_at', 'verified_at',
    ];

    /**
     *
     */
    const TYPE_REGISTRATION = 'registration';
    const TYPE_LOGIN = 'login';
    const TYPE_RESERVATION = 'reservation';
    const TYPE_CHANGE = 'change';
    const TYPE_RESET = 'reset';
    const TYPE_RESET_CONFIRMATION = 'reset_confirmation';
    const TYPE_VERIFY = 'verify';

    /**
     * @var string[]
     */
    protected $dates = [
        'expires_at', 'verified_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at < now();
    }

    /**
     * @return bool
     */
    public function isVerified()
    {
        return !is_null($this->verified_at);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeRegistration(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_REGISTRATION);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeLogin(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_LOGIN);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeChange(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_CHANGE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeReset(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_RESET);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeReservation(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_RESERVATION);
    }
}

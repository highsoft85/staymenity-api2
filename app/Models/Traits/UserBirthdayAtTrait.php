<?php

declare(strict_types=1);

namespace App\Models\Traits;

use Carbon\Carbon;

/**
 * Trait UserBirthdayAtTrait
 * @package App\Models\Traits
 *
 * @property Carbon|null $birthday_at
 */
trait UserBirthdayAtTrait
{
    /**
     * @return mixed
     */
    public function getBirthdayDayAttribute()
    {
        return !is_null($this->birthday_at) ? $this->birthday_at->format('d') : '';
    }

    /**
     * @return mixed
     */
    public function getBirthdayMonthAttribute()
    {
        return !is_null($this->birthday_at) ? $this->birthday_at->format('m') : '';
    }

    /**
     * @return mixed
     */
    public function getBirthdayYearAttribute()
    {
        return !is_null($this->birthday_at) ? $this->birthday_at->format('Y') : '';
    }

    /**
     * @param object|null $value
     */
    public function setBirthdayAtAttribute(?object $value)
    {
        $this->attributes['birthday_at'] = Carbon::parse($value)->format('Y-m-d');
        $this->attributes['age'] = Carbon::parse($value)->diffInYears(now());
    }
}

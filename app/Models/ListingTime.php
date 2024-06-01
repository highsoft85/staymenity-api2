<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ListingTime
 * @package App\Models
 *
 * @property int $id
 * @property int $listing_id
 * @property string|null $type
 * @property string $from
 * @property string $to
 *
 * @property Listing|null $listing
 *
 * * * METHODS
 * @method static weekdays()
 * @see \App\Models\ListingTime::scopeWeekdays()
 *
 * @method static weekends()
 * @see \App\Models\ListingTime::scopeWeekends()
 *
 */
class ListingTime extends Model
{
    /**
     * @var string
     */
    protected $table = 'listing_times';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'listing_id', 'type', 'from', 'to',
    ];

    const TYPE_WEEKDAYS = 'weekdays';
    const TYPE_WEEKENDS = 'weekends';

    /**
     *
     */
    public function listing()
    {
        $this->belongsTo(Listing::class);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWeekdays(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_WEEKDAYS);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeWeekends(Builder $query): Builder
    {
        return $query->where('type', self::TYPE_WEEKENDS);
    }
}

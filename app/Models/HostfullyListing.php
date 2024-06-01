<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HostfullyListing
 * @package App\Models
 *
 * @property int $id
 * @property string $uid
 * @property int $listing_id
 * @property array $external
 * @property Carbon|null $last_sync_at
 * @property int $is_channel_active
 *
 *
 * @property Listing|null $listing
 */
class HostfullyListing extends Model
{
    /**
     * @var string
     */
    protected $table = 'hostfully_listings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'listing_id', 'external', 'last_sync_at', 'is_channel_active',
    ];

    /**
     * @return array
     */
    public function getExternalArrayAttribute()
    {
        $data = !is_null($this->external) ? $this->external : '{}';
        return json_decode($data, true);
    }

    /**
     * @param array $value
     */
    public function setExternalAttribute(array $value)
    {
        $this->attributes['external'] = json_encode($value);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Listing
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }
}

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
 * @property int $reservation_id
 * @property array $external
 * @property Carbon|null $last_sync_at
 *
 * @property Reservation|null $reservation
 */
class HostfullyReservation extends Model
{
    /**
     * @var string
     */
    protected $table = 'hostfully_reservations';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'reservation_id', 'external', 'last_sync_at',
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
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|Reservation
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}

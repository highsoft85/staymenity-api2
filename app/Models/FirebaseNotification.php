<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\UsesUuid;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * Class UserSetting
 * @package App\Models
 *
 * @property int $id
 * @property string $type
 * @property string $notifiable_type
 * @property int $notifiable_id
 * @property string $data
 * @property array $dataArray
 * @property Carbon|null $read_at
 */
class FirebaseNotification extends Model
{
    use UsesUuid;

    /**
     * @var string
     */
    protected $table = 'firebase_notifications';
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type', 'notifiable_type', 'notifiable_id', 'data', 'read_at',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'read_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function notifiable()
    {
        return $this->morphTo();
    }

    /**
     * @param array $value
     */
    public function setDataAttribute(array $value)
    {
        $this->attributes['data'] = json_encode($value);
    }

    /**
     * @return array
     */
    public function getDataArrayAttribute()
    {
        return json_decode($this->data, true);
    }
}

<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class HostfullyWebhook
 * @package App\Models
 *
 * @property int $id
 * @property string $uid
 * @property string $agency_uid
 * @property string $object_uid
 * @property string $type
 * @property string $event_type
 * @property string $callback_url
 * @property array $external
 * @property Carbon|null $last_sync_at
 *
 *
 */
class HostfullyWebhookRequest extends Model
{
    /**
     * @var string
     */
    protected $table = 'hostfully_webhook_requests';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'agency_uid', 'object_uid', 'type', 'event_type', 'callback_url', 'external', 'last_sync_at',
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
}

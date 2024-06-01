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
 * @property string $agency_uid
 * @property string $event_type
 * @property string|null $lead_uid
 * @property string|null $property_uid
 * @property int $status
 *
 */
class HostfullyWebhookResponse extends Model
{
    /**
     * @var string
     */
    protected $table = 'hostfully_webhook_responses';

    const STATUS_HEALTH_CHECK_NOT_ACTIVE = 0;
    const STATUS_HEALTH_CHECK_ACTIVE = 1;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uid', 'agency_uid', 'event_type', 'lead_uid', 'property_uid', 'status',
    ];
}

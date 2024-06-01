<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSetting
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property int $notification_mail
 * @property int $notification_push
 * @property int $notification_messages
 */
class UserSetting extends Model
{
    /**
     * @var string
     */
    protected $table = 'user_settings';

    const NOTIFICATION_MAIL = 'mail';
    const NOTIFICATION_PUSH = 'push';
    const NOTIFICATION_MESSAGES = 'messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'notification_mail', 'notification_push', 'notification_messages'
    ];
}

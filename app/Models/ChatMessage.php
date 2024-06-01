<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Imageable;
use App\Services\Modelable\Statusable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ChatMessage
 * @package App\Models
 *
 * @property int $id
 * @property int $chat_id
 * @property int $user_id
 * @property string $text
 * @property Carbon|null $read_at
 * @property Carbon|null $send_at
 * @property Carbon|null $new_message_mailed_at
 * @property int $status
 *
 *
 * @property User|null $userTrashed
 * @property User|null $user
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\ChatMessage::scopeActive()
 *
 * @method static ordered()
 * @see \App\Models\ChatMessage::scopeOrdered()
 *
 * @method static orderedReverse()
 * @see \App\Models\ChatMessage::scopeOrderedReverse()
 */
class ChatMessage extends Model
{
    use Statusable;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @var string
     */
    protected $table = 'chat_messages';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'chat_id', 'user_id', 'text', 'read_at', 'send_at', 'new_message_mailed_at', 'status',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'read_at', 'send_at', 'new_message_mailed_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return mixed
     */
    public function userTrashed()
    {
        return $this->user()->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('send_at', 'desc');
    }

    /**
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrderedReverse(Builder $query): Builder
    {
        return $query->orderBy('send_at', 'asc');
    }
}

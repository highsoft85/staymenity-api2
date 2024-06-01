<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Statusable;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;

/**
 * Class Chat
 * @package App\Models
 *
 * @property int $id
 * @property int $owner_id
 * @property int $reservation_id
 * @property string $title
 * @property Carbon|null $last_message_at
 * @property int $status
 *
 * @property User|null $owner
 * @property User|null $creator
 * @property User|null $creatorTrashed
 * @property Reservation|null $reservation
 * @property Listing|null $listing
 * @property Listing|null $listingTrashed
 *
 * @property ChatMessage[]|Collection $messages
 * @property ChatMessage[]|Collection $messagesActive
 * @property ChatMessage[]|Collection $messagesActiveOrdered
 * @property ChatMessage[]|Collection $messagesActiveOrderedReverse
 *
 * @property User[] $users
 *
 *
 *
 * * * METHODS
 * @method static active()
 * @see \App\Models\Image::scopeActive()
 *
 * @method static ordered()
 * @see \App\Models\Image::scopeOrdered()
 *
 */
class Chat extends Model
{
    use Statusable;
    use SoftDeletes;

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @var string
     */
    protected $table = 'chats';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'owner_id', 'creator_id', 'listing_id', 'title', 'last_message_at', 'status',
    ];

    /**
     * @var array
     */
    protected $dates = [
        'last_message_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * @return mixed
     */
    public function creatorTrashed()
    {
        return $this->creator()->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function listing()
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * @return mixed
     */
    public function listingTrashed()
    {
        return $this->listing()->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|ChatMessage
     */
    public function messages()
    {
        return $this->hasMany(ChatMessage::class, 'chat_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'user_chat');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|ChatMessage
     */
    public function messagesActive()
    {
        return $this->messages()->active();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messagesActiveOrdered()
    {
        return $this->messagesActive()->ordered();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function messagesActiveOrderedReverse()
    {
        return $this->messagesActive()->orderedReverse();
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
        return $query->orderBy('last_message_at', 'desc');
    }
}

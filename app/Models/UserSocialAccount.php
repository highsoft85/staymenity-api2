<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class UserSocialAccount
 * @package App\Models
 *
 * @protected User $user
 *
 * @property string $provider
 * @property int $user_id
 *
 *
 * @property User|null $user
 */
class UserSocialAccount extends Model
{
    protected $fillable = [
        'user_id', 'provider_user_id', 'provider'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

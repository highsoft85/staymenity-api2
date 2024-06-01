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
class Visit extends Model
{
    /**
     * @var string
     */
    protected $table = 'visits';

    /**
     * @var array
     */
    protected $fillable = [
        'visitable_id', 'visitable_type', 'ip', 'expired_at'
    ];

    /**
     * @var array
     */
    protected $dates = [
        'expired_at',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function model()
    {
        return $this->morphTo();
    }
}

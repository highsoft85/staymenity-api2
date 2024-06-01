<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class SystemOptionValue
 * @package App\Models
 *
 * @property string $value
 */
class SystemOptionValue extends Model
{
    /**
     * @var string
     */
    protected $table = 'system_option_values';

    /**
     * @var array
     */
    protected $fillable = [
        'option_id', 'parameter_id', 'value', 'priority'
    ];

    /**
     * @return bool
     */
    public function isActive()
    {
        return true;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function option()
    {
        return $this->hasOne(Option::class, 'id', 'option_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function parameter()
    {
        return $this->hasOne(OptionParameter::class);
    }
}

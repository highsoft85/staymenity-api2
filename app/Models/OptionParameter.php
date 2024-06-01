<?php

declare(strict_types=1);

namespace App\Models;

use App\Services\Modelable\Statusable;
use Illuminate\Database\Eloquent\Model;

class OptionParameter extends Model
{
    use Statusable;

    /**
     * @var string
     */
    protected $table = 'option_parameters';

    /**
     * @var array
     */
    protected $fillable = [
        'option_id', 'quantity', 'priority', 'status',
    ];

    const STATUS_NOT_ACTIVE = 0;
    const STATUS_ACTIVE = 1;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function option()
    {
        return $this->hasOne(Option::class);
    }
}

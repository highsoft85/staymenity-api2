<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class ListingSetting
 * @package App\Models
 *
 * @property int $id
 * @property int $listing_id
 * @property string|null $amenities
 * @property string|null $rules
 * @property string|null $type
 * @property string|null $address_two
 * @property boolean|null $is_dedicated
 * @property boolean|null $is_company
 * @property boolean|null $is_rented_before
 * @property string|null $cancellation_description
 * @property int|null $people_max
 */
class ListingSetting extends Model
{
    /**
     * @var string
     */
    protected $table = 'listing_settings';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'listing_id', 'amenities', 'type', 'rules',
        'is_dedicated', 'is_company', 'is_rented_before',
        'cancellation_description', 'people_max',
        'address_two',
    ];
}

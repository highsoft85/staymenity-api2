<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use League\Fractal\TransformerAbstract;

class RatingTransformer extends TransformerAbstract
{
    /**
     * @param float $value
     * @param int $count
     * @return array
     */
    public function transform(float $value, int $count)
    {
        $value = (float)round($value, 2);
        $valueFormatted = number_format($value, 2);
        return [
            'value' => $value,
            'value_formatted' => $valueFormatted,
            'count' => $count,
        ];
    }
}

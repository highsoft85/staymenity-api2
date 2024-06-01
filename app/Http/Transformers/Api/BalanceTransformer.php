<?php

declare(strict_types=1);

namespace App\Http\Transformers\Api;

use App\Models\Balance;
use App\Models\Review;
use App\Models\Type;
use League\Fractal\TransformerAbstract;

class BalanceTransformer extends TransformerAbstract
{
    /**
     * @param Balance $oItem
     * @return array
     */
    public function transform(Balance $oItem)
    {
        return [
            'amount' => $oItem->amount,
            'status' => $oItem->status,
        ];
    }

    /**
     * @return array
     */
    public function transformEmpty()
    {
        return [
            'amount' => 0,
            'status' => 0,
        ];
    }
}

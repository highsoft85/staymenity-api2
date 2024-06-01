<?php

declare(strict_types=1);

namespace App\Cmf\Project\OptionSystemValue;

use App\Models\SystemOptionValue;
use Illuminate\Http\Request;

trait OptionSystemValueThisTrait
{
    public function thisDestroy($oItem)
    {
        // заглушка чтобы не было удаления
    }

    /**
     * @param Request $request
     * @param object|SystemOptionValue $oItem
     * @return array|\Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function thisUpdate(Request $request, SystemOptionValue $oItem)
    {
        $oItem->update([
            'value' => $request->get('value'),
        ]);
        return responseCommon()->success([], 'The record was updated.');
    }
}

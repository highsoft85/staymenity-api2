<?php

declare(strict_types=1);

namespace App\Cmf\Project\Payout;

use App\Models\Payout;
use Illuminate\Http\Request;

trait PayoutCustomTrait
{
    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionCancelPayout(Request $request, int $id)
    {
        $oPayout = Payout::find($id);

        return responseCommon()->success([], 'Success');
    }
}

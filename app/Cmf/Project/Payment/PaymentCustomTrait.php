<?php

declare(strict_types=1);

namespace App\Cmf\Project\Payment;

use App\Models\Payment;
use Illuminate\Http\Request;

trait PaymentCustomTrait
{
    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionCancelPayment(Request $request, int $id)
    {
        $oPayment = Payment::find($id);

        return responseCommon()->success([], 'Success');
    }
}

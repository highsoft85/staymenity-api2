<?php

declare(strict_types=1);

namespace App\Cmf\Project\Review;

use App\Models\Review;
use App\Services\Model\ListingServiceModel;
use App\Services\Model\ReviewServiceModel;
use Illuminate\Http\Request;

trait ReviewCustomTrait
{
    /**
     * @param Request $request
     * @param int $id
     * @return array
     */
    public function actionBanned(Request $request, int $id)
    {
        /** @var Review $oItem */
        $oItem = $this->findByClass($this->class, $id);
        $banned = (int)$request->get('banned');
        $oItem->update([
            'banned_at' => $banned === 1 ? now() : null,
            'status' => $banned === 1 ? Review::STATUS_NOT_ACTIVE : Review::STATUS_ACTIVE,
        ]);
        $oItem->refresh();
        if (!is_null($oItem->reservation) && !is_null($oItem->reservation->listing)) {
            (new ListingServiceModel($oItem->reservation->listing))->updateRating();
        }
        return responseCommon()->success([], $banned === 1 ? 'Review was banned' : 'Review was unbanned');
    }
}

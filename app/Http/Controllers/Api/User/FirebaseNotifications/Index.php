<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\FirebaseNotifications;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\FirebaseNotificationTransformer;
use App\Http\Transformers\Api\ListingTransformer;
use App\Http\Transformers\Api\NotificationTransformer;
use App\Http\Transformers\Api\UserSaveTransformer;
use App\Models\FirebaseNotification;
use App\Models\Listing;
use App\Models\UserSave;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Pagination\LengthAwarePaginator;

class Index extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        $limit = $request->get('limit') ?? 10;
        /** @var LengthAwarePaginator $oResult */
        $oResult = $oUser->firebaseNotifications()
            ->whereNull('read_at')
            // последние сверху
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
        $aItems = $oResult->values()->transform(function (FirebaseNotification $item) {
            return (new FirebaseNotificationTransformer())->transform($item);
        })->toArray();
        return responseCommon()->apiDataSuccessWithPagination($aItems, $oResult);
    }
}

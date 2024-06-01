<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\User\Notifications;

use App\Http\Controllers\Api\ApiController;
use App\Http\Transformers\Api\NotificationTransformer;
use App\Services\Firebase\FirebaseCounterNotificationsService;
use App\Services\Model\NotificationServiceModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;

class Index extends ApiController
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oUser = $this->authUser($request);

        if (!envIsDocumentation()) {
            (new FirebaseCounterNotificationsService())
                ->database()
                ->setUser($oUser)
                ->clear();
        }
        $oItems = $oUser->notifications()
            ->whereNull('read_at')
            // последние сверху
            ->orderBy('created_at', 'desc')
            ->get();

        if (!$request->exists('no_read')) {
            foreach ($oItems as $oItem) {
                $oItem->update([
                    'read_at' => now(),
                ]);
            }
        }
        $aItems = $oItems->transform(function (DatabaseNotification $item) use ($oUser) {
            return (new NotificationTransformer())->transformByUser($item, $oUser);
        })->toArray();
        return responseCommon()->apiDataSuccess($aItems);
    }
}

<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Index;

use App\Events\ChangeCacheEvent;
use App\Http\Requests\Api\Index\HostRequestRequest;
use App\Mail\User\UserHaveNewMessageMail;
use App\Models\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Mail;

class HostRequest
{
    /**
     * @param HostRequestRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(HostRequestRequest $request)
    {
        $data = $request->validated();

        slackInfo($data, 'HostRequest');
        Request::create([
            'type' => Request::TYPE_HOST,
            'name' => $data['name'],
            'email' => $data['email'],
            'external' => [
                'type' => $data['type'],
                'city' => $data['city'],
            ],
            'status' => Request::STATUS_UNREAD,
        ]);
        event(new ChangeCacheEvent('cmf:request:count'));

        return responseCommon()->apiSuccess([], 'Thank you for your request');
    }
}

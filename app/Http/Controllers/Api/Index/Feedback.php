<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Index;

use App\Http\Requests\Api\Index\FeedbackRequest;
use Illuminate\Http\JsonResponse;

class Feedback
{
    /**
     * @param FeedbackRequest $request
     * @return array|JsonResponse
     */
    public function __invoke(FeedbackRequest $request)
    {
        $data = $request->validated();

        \App\Models\Feedback::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'message' => $data['message'],
        ]);
        slackInfo($data, 'Feedback');

        return responseCommon()->apiSuccess([], 'Thank you for your feedback');
    }
}

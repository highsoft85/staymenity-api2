<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Index;

use App\Http\Transformers\Api\FaqTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class Faq
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $oItems = \App\Models\Faq::active()->ordered()->get();
        $aItems = $oItems->transform(function (\App\Models\Faq $item) {
            return (new FaqTransformer())->transform($item);
        })->toArray();
        return responseCommon()->apiDataSuccess($aItems);
    }
}

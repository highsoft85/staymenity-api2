<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\Search\Place;

use App\Http\Requests\Api\Listings\StoreRequest;
use App\Models\User;
use App\Services\Geocoder\GeocoderCitiesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use SKAgarwal\GoogleApi\Exceptions\GooglePlacesApiException;

class Index
{
    /**
     * @param Request $request
     * @return array|JsonResponse
     */
    public function __invoke(Request $request)
    {
        $data = $request->all();
        $validation = Validator::make($data, [
            'q' => ['required'],
        ]);
        if ($validation->fails()) {
            return responseCommon()->validationMessages($validation);
        }
        try {
            $result = (new GeocoderCitiesService())->place($data['q']);
            return responseCommon()->apiDataSuccess($result);
        } catch (GooglePlacesApiException $e) {
            return responseCommon()->apiNotFound([
                'external' => $e->getErrorMessage(),
            ]);
        }
    }
}

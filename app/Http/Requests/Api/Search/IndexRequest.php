<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Search;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Services\Search\SearchService;

class IndexRequest extends FormRequestCommon
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            SearchService::FIELD_DATE => [
                'string',
                'date_format:m-d-Y'
            ],
            'types' => ['array'],
            'types_all' => ['integer'],
            //'place_id' => ['required_without:location'],
            'place_id' => [],
            'location.latitude' => [
                // не обязателен, когда есть мод ближайшие
                //'required_unless:' . SearchService::FIELD_MODE . ',' . SearchService::MODE_NEARBY,
                'required_with:location'
            ],
            'location.longitude' => [
                // не обязателен, когда есть мод ближайшие
                //'required_unless:' . SearchService::FIELD_MODE . ',' . SearchService::MODE_NEARBY,
                'required_with:location'
            ],

            'map.0.latitude' => ['required_with:map'],
            'map.0.longitude' => ['required_with:map'],
            'map.1.latitude' => ['required_with:map'],
            'map.1.longitude' => ['required_with:map'],


            'price.from' => ['required_with:price'],
            'price.to' => ['required_with:price'],

            SearchService::FIELD_VERIFIED => ['integer'],

            SearchService::FIELD_AMENITIES => ['array'],
            SearchService::FIELD_RULES => ['array'],

            SearchService::FIELD_GUESTS_SIZE => ['integer'],
            SearchService::FIELD_RENT_TIME_MIN => ['integer'],
            SearchService::FIELD_HOURS => ['integer'],

            SearchService::FIELD_NO_DEPOSIT => ['integer'],
            SearchService::FIELD_NO_CLEANING_FEE => ['integer'],
            SearchService::FIELD_MODE => ['string'],

            // когда поиск по карте происходит внутри сохраненного списка
            SearchService::FIELD_USER_SAVE_ID => [
                'integer',
            ],

            // поиск по походим листингам на ID
            SearchService::FIELD_SIMILAR_ID => [
                'integer',
            ],
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [];
    }

    /**
     * @see ForgotPasswordStrategy
     * @return array
     */
    public function bodyParameters()
    {
        return [];
    }
}

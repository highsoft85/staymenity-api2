<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Listings;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\Amenity;
use App\Models\Rule;
use App\Models\Type;
use App\Models\User;
use App\Services\Model\ListingServiceModel;

class UpdateRequest extends FormRequestCommon
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $oType = Type::other()->first();
        //$oAmenity = Amenity::other()->first();
        //$oRule = Rule::other()->first();

        return [
            'amenities' => ['array'],
            'amenities_other' => ['string'],
            'images' => ['array', 'nullable'],

            // для отслеживания загружаемая фотка для главного изображения или нет
            'image_set_main' => ['integer'],

            'description' => ['string'],
            'title' => ['string'],
            'rules' => ['array'],
            'rules_other' => ['string'],
            // rent time
            'price' => ['integer'],
            'deposit' => ['integer', 'nullable'],
            'cleaning_fee' => ['integer', 'nullable'],
            'type_id' => ['integer'],
            'type_other' => ['string', 'required_if:type_id,' . $oType->id],
            'guests_size' => ['integer'],
            'rent_time_min' => ['integer'],
            'place_id' => ['string'],
            'address_two' => ['string'],
            'times' => ['array'],

            'hostfully_property_uid' => ['string', 'nullable'],

            // unlist
            'status' => [
                //'nullable',
                \Illuminate\Validation\Rule::in([
                    ListingServiceModel::STATUS_UNLIST,
                    ListingServiceModel::STATUS_PUBLISH,
                ]),
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

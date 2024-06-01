<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Listings;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;

class StoreRequest extends FormRequestCommon
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'type_id' => ['required'],
            'type_other' => ['string'],
            'guests_size' => ['required'],
            'address' => ['required'],
            'place_id' => ['required'],
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

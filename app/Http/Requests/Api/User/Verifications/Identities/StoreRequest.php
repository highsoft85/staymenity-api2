<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Verifications\Identities;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Controllers\Api\ApiCommonTrait;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\User;
use App\Models\UserIdentity;
use App\Services\Search\SearchService;
use Illuminate\Validation\Rule;

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
//            'type' => [
//                'required',
//                'string',
//                Rule::in([
//                    UserIdentity::TYPE_PASSPORT,
//                    UserIdentity::TYPE_DRIVERS,
//                    UserIdentity::TYPE_ID,
//                ]),
//            ],
//            'image_front' => [
//                'required', 'max:5000', 'mimes:jpg,jpeg,gif,png',
//            ],
//            'image_back' => [
//                'required_if:type,' . UserIdentity::TYPE_DRIVERS, 'max:5000', 'mimes:jpg,jpeg,gif,png',
//            ],
//            'image_selfie' => [
//                'required', 'max:5000', 'mimes:jpg,jpeg,gif,png',
//            ],
//            'example_error' => ['integer']
        ];
    }

    /**
     * Custom message for validation
     *
     * @return array
     */
    public function messages()
    {
        return [
//            'image_front.required' => 'Front image: required',
//            'image_front.mimes' => 'Front image: must be a file of type: jpg, jpeg, gif, png.',
//            'image_front.max' => 'Front image: may not be greater than 5000 kilobytes.',
//
//            'image_back.required_if' => 'Back image: required',
//            'image_back.mimes' => 'Back image: must be a file of type: jpg, jpeg, gif, png.',
//            'image_back.max' => 'Back image: may not be greater than 5000 kilobytes.',
//
//            'image_selfie.required' => 'Selfie image: required',
//            'image_selfie.mimes' => 'Selfie image: must be a file of type: jpg, jpeg, gif, png.',
//            'image_selfie.max' => 'Selfie image: may not be greater than 5000 kilobytes.',
        ];
    }

    /**
     * @return array
     * @see ForgotPasswordStrategy
     */
    public function bodyParameters()
    {
        return [];
    }
}

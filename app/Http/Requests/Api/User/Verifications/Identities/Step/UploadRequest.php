<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Verifications\Identities\Step;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Controllers\Api\ApiCommonTrait;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\User;
use App\Models\UserIdentity;
use App\Services\Image\ImageType;
use App\Services\Search\SearchService;
use Illuminate\Validation\Rule;

class UploadRequest extends FormRequestCommon
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'image' => [
                'required', 'max:5000', 'mimes:jpg,jpeg,gif,png',
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

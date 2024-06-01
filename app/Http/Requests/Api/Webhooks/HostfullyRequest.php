<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\Webhooks;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Services\Search\SearchService;

class HostfullyRequest extends FormRequestCommon
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'agency_uid' => [
                'required',
                'string',
            ],
            'event_type' => [
                'required',
                'string',
            ],
            'lead_uid' => [
                'sometimes',
                'string',
            ],
            'property_uid' => [
                'sometimes',
                'string',
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

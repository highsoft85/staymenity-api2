<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Settings\Notifications;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Controllers\Api\ApiCommonTrait;
use App\Http\Requests\Api\FormRequestCommon;
use App\Models\User;
use App\Services\Search\SearchService;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequestCommon
{
    use ApiCommonTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required',
                Rule::in(['mail', 'push', 'messages']),
            ],
            'enable' => [
                'required',
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

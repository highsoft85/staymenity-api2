<?php

declare(strict_types=1);

namespace App\Http\Requests\Api\User\Reservations;

use App\Docs\Strategies\BodyParameters\Auth\ForgotPasswordStrategy;
use App\Http\Requests\Api\FormRequestCommon;
use App\Http\Requests\Api\ReservationRulesTrait;

class StoreRequest extends FormRequestCommon
{
    use ReservationRulesTrait;

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return $this->rulesForUser();
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

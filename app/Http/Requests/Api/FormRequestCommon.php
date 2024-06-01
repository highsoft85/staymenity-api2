<?php

declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Exceptions\ResourceExceptionValidation;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;

class FormRequestCommon extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * @param Validator $validator
     * @return void
     *
     * @throws ValidationException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new ResourceExceptionValidation(null, responseCommon()->validationGetMessages($validator));
    }
}

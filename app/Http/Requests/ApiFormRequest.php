<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Helpers\ApiResponse;

abstract class ApiFormRequest extends FormRequest
{
    /**
     * Override failed validation response to use ApiResponse.
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            ApiResponse::error(
                __('validation.failed'),
                $validator->errors()->all(),
                422
            )
        );
    }
}
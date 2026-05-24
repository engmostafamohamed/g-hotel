<?php

namespace App\Http\Requests\V1\CRM\Offer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateOfferRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('employee')->user()?->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'type' => 'sometimes|string',
            'value' => 'sometimes|numeric|min:0',
            'inventory.total' => 'sometimes|integer|min:1',
            'inventory.per_guest' => 'sometimes|integer|min:1',
            'valid_dates' => 'sometimes|array|size:2',
            'valid_dates.0' => 'required_with:valid_dates|date',
            'valid_dates.1' => 'required_with:valid_dates|date|after_or_equal:valid_dates.0'
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'statusCode' => 422,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422));
    }
}

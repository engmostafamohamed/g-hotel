<?php

namespace App\Http\Requests\V1\CRM\Offer;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreOfferRequest extends FormRequest
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
            'type' => 'required|string',
            'value' => 'required|numeric|min:0',
            'inventory.total' => 'required|integer|min:1',
            'inventory.per_guest' => 'required|integer|min:1',
            'valid_dates' => 'required|array|size:2',
            'valid_dates.0' => 'required|date',
            'valid_dates.1' => 'required|date|after_or_equal:valid_dates.0',
            'service_id' => 'required|exists:services,id',
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

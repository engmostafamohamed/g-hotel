<?php

namespace App\Http\Requests\V1\CRM\HotelLocation;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateHotelLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth('employee')->user();

        return $user && $user->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'location_name' => 'sometimes|string',
            'property_code' => 'sometimes|string|unique:hotel_locations,property_code',
            'display_name' => 'sometimes|string|max:255',
            'default_language' => 'sometimes|string|in:en,ar',
            'default_currency' => 'sometimes|string|in:USD,EGP',
            'lat' => "sometimes|string",
            'long' => "sometimes|string",
            'address' => "sometimes|string",
            'hotel_video_url' => "sometimes|url",
            'timezone' => 'sometimes|string|timezone',
            'is_active' => 'boolean',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'statusCode' => 422,
            'message' => 'Hotel Location could not be updated.',
            'errors' => $validator->errors()
        ], 422));
    }
}

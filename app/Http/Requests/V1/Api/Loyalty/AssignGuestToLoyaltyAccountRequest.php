<?php

namespace App\Http\Requests\V1\Api\Loyalty;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AssignGuestToLoyaltyAccountRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('guest')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hotel_id' => 'required|exists:hotel_locations,id',
            'guest_id' => 'required|exists:guests,id',
            'pointEarned' => 'optional|integer',
            'pointRedeemed' => 'optional|integer',
            'tier_id' => 'optional|integer',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'statusCode' => 422,
            'message' => 'Loyalty Account could not be created.',
            'errors' => $validator->errors()
        ], 422));
    }
}

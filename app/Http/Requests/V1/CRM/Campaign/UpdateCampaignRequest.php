<?php

namespace App\Http\Requests\V1\CRM\Campaign;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth('employee')->user();

        return $user && $user->hasRole('marketing');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string|max:255',
            'channels' => 'sometimes|array',
            'channels.*' => 'in:email,app_push',
            'estimated_reach' => 'numeric|min:1',
            // 'approval_required' => 'boolean|min:1',
            'offer.type' => 'sometimes|string|in:points_discount,percentage,fixed',
            'offer.value' => 'sometimes|string|max:255',
            'offer.min_booking' => 'sometimes|numeric|min:0',
            'content_preview.email' => 'nullable|string',
            'content_preview.push' => 'nullable|string|max:255',
        ];
    }
    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => false,
            'statusCode' => 422,
            'message' => 'Campaign could not be created.',
            'errors' => $validator->errors()
        ], 422));
    }
}

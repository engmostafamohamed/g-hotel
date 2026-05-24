<?php

namespace App\Http\Requests\V1\CRM\Campaign;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreCampaignRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // $user = auth('employee')->user();
        // return $user && $user->hasRole('marketing');

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:campaigns,name',
            'channels' => 'required|array|min:1',
            'channels.*' => 'in:email,app_push',
            'estimated_reach' => 'numeric|min:1',
            'approval_required' => 'boolean|min:1',

            'offer.type' => 'required|string|in:points_discount,percentage_discount,fixed_discount,free_service',
            'offer.value' => 'required|string',
            'offer.min_booking' => 'required|numeric|min:0',

            'preview.email_html' => 'nullable|string',
            'preview.push_message' => 'nullable|string|max:255',
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

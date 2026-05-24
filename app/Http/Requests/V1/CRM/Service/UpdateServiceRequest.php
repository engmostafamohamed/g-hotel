<?php

namespace App\Http\Requests\V1\CRM\Service;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('employee')->check() && auth('employee')->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'sometimes|string',
            'description' => 'nullable|string',
            'price' => 'numeric|min:0',
            'category' => 'sometimes|string|exists:service_categories,name',
            'locations' => 'nullable|array|min:1',
            'locations.*' => 'string',
            'image' => 'nullable|image',
            'sync_with_pms' => 'boolean',
            'time_slots' => 'nullable|array|min:1',
            'time_slots.*.start' => 'required_with:time_slots|date_format:H:i',
            'time_slots.*.end' => 'required_with:time_slots|date_format:H:i|after:time_slots.*.start',
            'time_slots.*.max_capacity' => 'required_with:time_slots|integer|min:1',
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

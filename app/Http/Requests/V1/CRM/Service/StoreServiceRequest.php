<?php

namespace App\Http\Requests\V1\CRM\Service;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreServiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // return auth('employee')->check() && auth('employee')->user()->hasRole('admin');
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
            'name' => 'required|string|unique:services,name',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|exists:service_categories,name',
            'locations' => 'required|array|min:1',
            'locations.*' => 'string',
            'time_slots' => 'required|array|min:1',
            'time_slots.*.start' => 'required|date_format:H:i',
            'time_slots.*.end' => 'required|date_format:H:i|after:time_slots.*.start',
            'time_slots.*.max_capacity' => 'required|integer|min:1',
            'image' => 'nullable|image',
            'sync_with_pms' => 'boolean',
            'hotel_id' => 'required|string|exists:hotel_locations,id'
        ];
    }
    public function messages(): array
    {
        return [
            'name.required' => 'The service name is required.',
            'name.unique' => 'The service name must be unique.',
            'category.required' => 'The category is required.',
            'locations.required' => 'At least one location must be specified.',
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

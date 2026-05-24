<?php

namespace App\Http\Requests\V1\CRM\ServiceCategory;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateeServiceCategoryRequest extends FormRequest
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
            'name' => 'sometimes|string|unique:service_categories,name,' . $this->category,
            'description' => 'nullable|string',
            'type' => 'sometimes|string|in:restaurant,in_room_dining,spa',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('serviceCategory.name.required'),
            'name.unique' => __('serviceCategory.name.unique'),
            'description.string' => __('serviceCategory.description.string'),
            'type.required' => __('serviceCategory.type.required'),
            'type.in' => __('serviceCategory.type.in'),
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

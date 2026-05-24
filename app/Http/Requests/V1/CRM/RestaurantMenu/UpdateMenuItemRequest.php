<?php

namespace App\Http\Requests\V1\CRM\RestaurantMenu;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UpdateMenuItemRequest extends FormRequest
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
            'name' => 'sometimes|array',
            'description' => 'sometimes|array',
            'price' => 'sometimes|numeric',
            'image_path' =>'sometimes|file|image|mimes:jpg,jpeg,png|max:2048',
            'dietary_tags' => 'nullable|array',
            'dietary_tags.*' => 'string',
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

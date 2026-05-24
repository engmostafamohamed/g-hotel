<?php

namespace App\Http\Requests\V1\CRM\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class CategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can add extra authorization logic if needed
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
     public function rules(): array
    {
        return [
            'category_id' => 'nullable|integer|exists:categories,id',
        ];
    }
    public function messages(): array
    {
        return [
            // 'hotel_id.integer' => __('category.hotel_id_integer'),
            // 'hotel_id.exists' => __('category.hotel_id_not_found'),

            'category_id.integer' => __('category.category_id_integer'),
            'category_id.exists' => __('category.category_id_not_found'),
        ];
    }
}

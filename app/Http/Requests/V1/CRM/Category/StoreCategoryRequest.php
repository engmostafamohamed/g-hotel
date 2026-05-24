<?php

namespace App\Http\Requests\V1\CRM\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class StoreCategoryRequest extends FormRequest
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
            'hotel_id' => 'required|integer|exists:hotel_locations,id',
            'category_images' => 'required|array|min:1',
            'category_images.*' => 'file|mimes:jpg,jpeg,png,webp|max:5120',
            'category_name' => 'required|array',
            'category_name.en' => 'required|string|max:255',
            'category_name.ar' => 'required|string|max:255',
            'category_description' => 'required|array',
            'category_description.en' => 'required|string|max:1000',
            'category_description.ar' => 'required|string|max:1000',
            'policies' => 'nullable|array',
            'policies.en' => 'nullable|array',
            'policies.en.*' => 'string|max:255',
            'policies.ar' => 'nullable|array',
            'policies.ar.*' => 'string|max:255',


            'feature_ids' => 'nullable|array',
            'feature_ids.*' => [
                'integer',
                Rule::exists('features', 'id')->where(function ($query) {
                    $query->where('hotel_id', $this->input('hotel_id'))
                        ->whereNull('deleted_at');
                }),
            ],

            'bed_data' => 'nullable|array',
            'bed_data.*.bed_id' => [
                'required_with:bed_data',
                'integer',
                Rule::exists('beds', 'id'),
            ],
            'bed_data.*.quantity' => 'required_with:bed_data|integer|min:1',


            'max_adults' => 'required|integer|min:1',
            'max_children' => 'required|integer|min:0',
            'infants_allowed' => 'required|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'feature_ids.*.integer' => __('feature.feature_id_integer'),
            'feature_ids.*.exists' => __('feature.feature_not_found'),
            'hotel_id.required' => __('category.hotel_id_required'),
            'hotel_id.integer' => __('category.hotel_id_integer'),
            'hotel_id.exists' => __('category.hotel_id_not_found'),
            'category_id.required' => __('category.category_id_required'),
            'category_id.integer' => __('category.category_id_integer'),
            'category_id.exists' => __('category.category_id_not_found'),

            'category_images.required' => __('category.images_required'),
            'category_images.array' => __('category.images_array'),
            'category_images.*.file' => __('category.image_file'),
            'category_images.*.mimes' => __('category.image_mimes'),
            'category_images.*.max' => __('category.image_max'),

            'category_name.en.required' => __('category.category_name_en_required'),
            'category_name.ar.required' => __('category.category_name_ar_required'),

            'category_description.en.required' => __('category.category_description_en_required'),
            'category_description.ar.required' => __('category.category_description_ar_required'),


            'max_adults.required' => __('category.max_adults_required'),
            'max_adults.integer' => __('category.max_adults_integer'),
            'max_adults.min' => __('category.max_adults_min'),

            'max_children.required' => __('category.max_children_required'),
            'max_children.integer' => __('category.max_children_integer'),
            'max_children.min' => __('category.max_children_min'),

            'infants_allowed.required' => __('category.infants_allowed_required'),
            'infants_allowed.boolean' => __('category.infants_allowed_boolean'),

            'policies.array' => __('category.policies_array'),
            'policies.en.array' => __('category.policies_en_array'),
            'policies.en.*.string' => __('category.policies_en_string'),
            'policies.ar.array' => __('category.policies_ar_array'),
            'policies.ar.*.string' => __('category.policies_ar_string'),
        ];
    }
}

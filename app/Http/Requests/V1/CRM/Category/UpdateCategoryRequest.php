<?php

namespace App\Http\Requests\V1\CRM\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateCategoryRequest extends FormRequest
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
    protected function prepareForValidation(): void
    {
        $this->merge([
            'category_id' => $this->route('id'),
            'hotel_id' => $this->input('hotel_id'),
        ]);
    }
     public function rules(): array
    {
        return [
            'hotel_id' => 'required|integer|exists:hotel_locations,id',
            'category_images' => 'nullable|array|min:1',
            'category_images.*' => 'file|mimes:jpg,jpeg,png,webp|max:5120',
            'category_name' => 'nullable|array',
            'category_name.en' => 'nullable|string|max:255',
            'category_name.ar' => 'nullable|string|max:255',
            'category_description' => 'nullable|array',
            'category_description.en' => 'nullable|string',
            'category_description.ar' => 'nullable|string',
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

            'max_adults' => 'nullable|integer|min:1',
            'max_children' => 'nullable|integer|min:0',
            'infants_allowed' => 'nullable|boolean',
            'policies' => 'nullable|array',
            'policies.en' => 'nullable|array',
            'policies.en.*' => 'string|max:255',
            'policies.ar' => 'nullable|array',
            'policies.ar.*' => 'string|max:255',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('category_id', [
            'required',
            Rule::exists('categories', 'id')->where(function ($query) {
                $query->where('hotel_id', $this->input('hotel_id'))
                      ->whereNull('deleted_at');
            }),
        ], function () {
            return $this->hasHeader('hotel_id') && is_numeric($this->input('hotel_id'));
        });
    }
    public function messages(): array
    {
        return [
            'feature_ids.*.integer' => __('feature.feature_id_integer'),
            'feature_ids.*.exists' => __('feature.feature_not_found'),
            'hotel_id.integer' => __('category.hotel_id_integer'),
            'hotel_id.exists' => __('category.hotel_id_not_found'),

            'category_id.integer' => __('category.category_id_integer'),
            'category_id.exists' => __('category.category_id_not_found'),

            'category_images.array' => __('category.images_array'),
            'category_images.*.file' => __('category.image_file'),
            'category_images.*.mimes' => __('category.image_mimes'),
            'category_images.*.max' => __('category.image_max'),

            
            'max_adults.integer' => __('category.max_adults_integer'),
            'max_adults.min' => __('category.max_adults_min'),

            'max_children.integer' => __('category.max_children_integer'),
            'max_children.min' => __('category.max_children_min'),

            'infants_allowed.boolean' => __('category.infants_allowed_boolean'),

            'policies.array' => __('category.policies_array'),
            'policies.en.array' => __('category.policies_en_array'),
            'policies.en.*.string' => __('category.policies_en_string'),
            'policies.ar.array' => __('category.policies_ar_array'),
            'policies.ar.*.string' => __('category.policies_ar_string'),

        ];
    }
}

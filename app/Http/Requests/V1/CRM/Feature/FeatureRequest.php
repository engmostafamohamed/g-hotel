<?php

namespace App\Http\Requests\V1\CRM\Feature;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class FeatureRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'feature_id' => $this->route('id'),
            'hotel_id' => $this->input('hotel_id'),
        ]);
    }

    public function rules(): array
    {
        return [
            'hotel_id' => 'required|integer|exists:hotel_locations,id',

        ];
    }

    public function withValidator($validator): void
    {
        $validator->sometimes('feature_id', [
            'required',
            'integer',
            Rule::exists('features', 'id')->where(function ($query) {
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
            'hotel_id.required' => __('feature.hotel_id_required'),
            'hotel_id.integer' => __('feature.hotel_id_integer'),
            'hotel_id.exists' => __('feature.hotel_id_not_found'),

            'feature_id.required' => __('feature.feature_id_required'),
            'feature_id.integer' => __('feature.feature_id_integer'),
            'feature_id.exists' => __('feature.feature_id_not_found'),
        ];
    }
}

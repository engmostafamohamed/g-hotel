<?php

namespace App\Http\Requests\V1\CRM\Loyalty\Tier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'tier_id' => $this->route('id'),
            // 'hotel_id' => $this->input('hotel_id'),
        ]);
    }

    public function rules(): array
    {
        return [
            // 'hotel_id' => 'required|integer|exists:hotel_locations,id',
            'tier_id' => 'required|integer|exists:loyalty_tiers,id',

            'tier_name' => 'nullable|array',
            'tier_name.en' => 'nullable|string|max:255',
            'tier_name.ar' => 'nullable|string|max:255',

            'code' => 'nullable|string',
            'threshold' => 'nullable|numeric',
            'content' => 'nullable|array',
            'content.en' => 'nullable|string|max:255',
            'content.ar' => 'nullable|string|max:255',
            // 'service_ids' => 'nullable|array',
            // 'service_ids.*' => [
            //     'integer',
            //     Rule::exists('services', 'id')->where(function ($query) {
            //         $query->where('hotel_id', $this->input('hotel_id'))
            //               ->whereNull('deleted_at');
            //     }),
            // ],
        ];
    }

    public function withValidator($validator): void
    {
        // $validator->sometimes('tier_id', [
        //     'required',
        //     'integer',
        //     Rule::exists('tiers', 'id')->where(function ($query) {
        //         $query->where('hotel_id', $this->input('hotel_id'))
        //               ->whereNull('deleted_at');
        //     }),
        // ], function () {
        //     return $this->hasHeader('hotel_id') && is_numeric($this->input('hotel_id'));
        // });
    }

    public function messages(): array
    {
        return [
            // 'hotel_id.required' => __('tier.hotel_id_required'),
            // 'hotel_id.integer' => __('tier.hotel_id_integer'),
            // 'hotel_id.exists' => __('tier.hotel_id_not_found'),

            'tier_id.required' => __('tier.tier_id_required'),
            'tier_id.integer' => __('tier.tier_id_integer'),
            'tier_id.exists' => __('tier.tier_id_not_found'),

            'tier_name.array' => __('tier.tier_name_array'),
            'tier_name.en.string' => __('tier.tier_name_en_string'),
            'tier_name.en.max' => __('tier.tier_name_en_max'),
            'tier_name.ar.string' => __('tier.tier_name_ar_string'),
            'tier_name.ar.max' => __('tier.tier_name_ar_max'),

            'code.string' => __('tier.code_string'),

            'threshold.numeric' => __('tier.tier_value_numeric'),
            // 'tier_value.min' => __('tier.tier_value_min'),
            'content.array' => __('tier.content_array'),

            'service_ids.array' => __('tier.service_ids_array'),
            'service_ids.*.integer' => __('tier.service_ids_must_be_integer'),
            'service_ids.*.exists' => __('tier.service_ids_invalid'),
        ];
    }
}

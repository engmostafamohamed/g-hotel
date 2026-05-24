<?php

namespace App\Http\Requests\V1\CRM\Loyalty\Tier;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'hotel_id' => 'required|integer|exists:hotel_locations,id',
            'tier_name' => 'required|array',
            'tier_name.ar' => 'required|string|max:255',
            'tier_name.en' => 'required|string|max:255',
            'code' => [
                'required',
                'string',
                Rule::unique('loyalty_tiers', 'code')->whereNull('deleted_at'),
            ],
            'threshold' => 'required|numeric',
            'content' => 'nullable|array',
            'content.ar' => 'nullable|string|max:255',
            'content.en' => 'nullable|string|max:255',
            // 'service_ids' => 'nullable|array',
            // 'service_ids.*' => [
            //     'integer',
            //     Rule::exists('services', 'id')
            //         ->where(function ($query) {
            //             $query->where('hotel_id', $this->input('hotel_id'))
            //                   ->whereNull('deleted_at');
            //         }),
            // ],
        ];
    }

    public function messages(): array
    {
        return [
            // English
            // 'hotel_id.required' => __('tier.hotel_id_required'),
            // 'hotel_id.integer' => __('tier.hotel_id_integer'),
            // 'hotel_id.exists' => __('tier.hotel_id_exists'),

            'tier_name.required' => __('loyalty.tier.tier_name_required'),
            'tier_name.array' => __('loyalty.tier.tier_name_array'),
            'tier_name.ar.required' => __('loyalty.tier.tier_name_ar_required'),
            'tier_name.en.required' => __('loyalty.tier.tier_name_en_required'),
            'tier_name.en.string' => __('loyalty.tier.tier_name_en_string'),
            'tier_name.en.max' => __('loyalty.tier.tier_name_en_max'),
            'tier_name.ar.string' => __('loyalty.tier.tier_name_ar_string'),
            'tier_name.ar.max' => __('loyalty.tier.tier_name_ar_max'),

            'code.required' => __('loyalty.tier.code_required'),
            'code.string' => __('loyalty.tier.code_string'),
            'code.unique' => __('loyalty.tier.code_unique'),

            'threshold.required' => __('loyalty.tier.tier_value_required'),
            'threshold.numeric' => __('loyalty.tier.tier_value_numeric'),
            // 'tier_value.min' => __('tier.tier_value_min'),
            'content.array' => __('loyalty.tier.content_array'),
            'content.en.string' => __('loyalty.tier.content_en_string'),
            'content.en.max' => __('loyalty.tier.content_en_max'),
            'content.ar.string' => __('loyalty.tier.content_ar_string'),
            'content.ar.max' => __('loyalty.tier.content_ar_max'),
            // 'service_ids.array' => __('tier.service_ids_array'),
            // 'service_ids.*.exists' => __('tier.service_ids_invalid'),
            // 'service_ids.*.integer' => __('tier.service_ids_must_be_integer'),
        ];
    }
}

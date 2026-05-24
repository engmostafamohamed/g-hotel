<?php

namespace App\Http\Requests\V1\CRM\Loyalty\Reward;

use Illuminate\Foundation\Http\FormRequest;

class StoreRewardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
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
            'reward_name'        => 'required|array',
            'reward_name.ar'     => 'required|string|max:255',
            'reward_name.en'     => 'required|string|max:255',
            'sku'                => 'required|string|unique:loyalty_rewards,sku',
            'cost_points'        => 'required|numeric|min:0',
            'stock'              => 'nullable|numeric|min:0',
            // 'meta'               => 'nullable|string',
            'active'             => 'boolean',
        ];

    }
    public function messages(): array
    {
        return [
            'reward_name.ar.required'     => __('loyalty.reward.name_ar_required'),
            'reward_name.ar.string'       => __('loyalty.reward.name_ar_string'),
            'reward_name.en.required'     => __('loyalty.reward.name_en_required'),
            'reward_name.en.string'       => __('loyalty.reward.name_en_string'),
            'sku.required'         => __('loyalty.reward.sku_required'),
            'sku.unique'           => __('loyalty.reward.sku_unique'),
            'cost_points.required' => __('loyalty.reward.cost_points_required'),
            'cost_points.numeric'  => __('loyalty.reward.cost_points_numeric'),
            'cost_points.min'      => __('loyalty.reward.cost_points_min'),
            'stock.numeric'        => __('loyalty.reward.stock_numeric'),
            'stock.min'            => __('loyalty.reward.stock_min'),
            'meta.string'          => __('loyalty.reward.meta_string'),
            'active.boolean'       => __('loyalty.reward.active_boolean'),
        ];
    }
}

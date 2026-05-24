<?php

namespace App\Http\Requests\V1\CRM\Loyalty\Reward;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class UpdateRewardRequest extends FormRequest
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
    public function validationData(): array
    {
        return array_merge($this->all(), [
            'id' => $this->route('id'),
        ]);
    }
    public function rules(): array
    {
        return [
            //
            'reward_name' => 'nullable|array',
            'reward_name.ar' => 'nullable|string|max:255',
            'reward_name.en' => 'nullable|string|max:255',
            'sku' => 'nullable|string|unique:loyalty_rewards,sku',
            'cost_points' => 'nullable|numeric|min:0',
            'stock' => 'nullable|numeric|min:0',
            // 'description_ar' => 'nullable|string',
            // 'description_en' => 'nullable|string',
            'active' => 'nullable|bool',
            // check reward id exists
            'id' => [
                'required',
                Rule::exists('loyalty_rewards', 'id'),
            ],
        ];
    }
    public function messages(): array
    {
        return [
            'reward_name.ar' => __('reward.name_string'),
            'reward_name.en.string' => __('reward.name_string'),
            'reward_name.ar.max' => __('reward.name_max'),
            'reward_name.en.max' => __('reward.name_max'),
            'sku.unique'         => __('loyalty.reward.sku_unique'),
            'cost_points.numeric'  => __('loyalty.reward.cost_points_numeric'),
            'cost_points.min'      => __('loyalty.reward.cost_points_min'),
            // 'description_ar.string' => __('reward.description_string'),
            // 'description_en.string' => __('reward.description_string'),
            'stock.numeric'        => __('loyalty.reward.stock_numeric'),
            'stock.min'            => __('loyalty.reward.stock_min'),
            // 'meta.string'          => __('loyalty.reward.meta_string'),
            'active.boolean'       => __('loyalty.reward.active_boolean'),
            'id.exists' => __('loyalty.reward.reward_not_found'),

        ];
    }
}

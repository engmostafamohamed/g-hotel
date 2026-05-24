<?php

namespace App\Http\Requests\V1\CRM\Loyalty\LoyaltyAccount;

use Illuminate\Foundation\Http\FormRequest;

class StoreLoyaltyAccountRequest extends FormRequest
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
            //
            'balance'=>'required|numeric',
            'lifetime_earned'=>'required|numeric',
            'lifetime_redeemed'=>'required|numeric',
            'user_id'=>'required|integer|exists:users,id',
            'tier_id'=>'required|integer|exists:loyalty_tiers,id',
        ];
    }

    public function messages(){
        return[
        'balance.required' => __('loyalty.loyaltyAccount.balance_required'),
        'balance.numeric' => __('loyalty.loyaltyAccount.balance_numeric'),
        'lifetime_earned.required' => __('loyalty.loyaltyAccount.lifetime_earned_required'),
        'lifetime_earned.numeric' => __('loyalty.loyaltyAccount.lifetime_earned_numeric'),
        'lifetime_redeemed.required' => __('loyalty.loyaltyAccount.lifetime_redeemed_required'),
        'user_id.required' => __('loyalty.loyaltyAccount.user_id_required'),
        'tier_id.required' => __('loyalty.loyaltyAccount.tier_id_required'),
        'user_id.exists' => __('loyalty.loyaltyAccount.user_not_found'),
        'tier_id.exists' => __('loyalty.loyaltyAccount.tier_not_found'),
        ];
    }
}

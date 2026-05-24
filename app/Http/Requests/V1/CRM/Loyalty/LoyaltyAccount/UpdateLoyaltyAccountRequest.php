<?php

namespace App\Http\Requests\V1\CRM\Loyalty\LoyaltyAccount;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLoyaltyAccountRequest extends FormRequest
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
            'loyaltyAccount_id' => 'required|integer|exists:loyalty_accounts,id',
            'balance'=>'nullable|numeric',
            'lifetime_earned'=>'nullable|numeric',
            'lifetime_redeemed'=>'nullable|numeric',
            'tier_id'=>'nullable|integer|exists:loyalty_tiers,id',
        ];
    }
    public function messages(){
        return[
            'loyaltyAccount_id.required' => __('loyalty.loyaltyAccount.loyaltyAccount_id_required'),
            'loyaltyAccount_id.integer' => __('loyalty.loyaltyAccount.loyaltyAccount_id_integer'),
            'loyaltyAccount_id.exists' => __('loyalty.loyaltyAccount.loyaltyAccount_id_not_found'),
            'balance.numeric' => __('loyalty.loyaltyAccount.loyaltyAccount_id_not_found'),
            'lifetime_earned.numeric' => __('loyalty.loyaltyAccount.lifetime_earned_integer'),

            'lifetime_redeemed.numeric' => __('loyalty.loyaltyAccount.lifetime_redeemed_integer'),
            'tier_id.integer' => __('loyalty.loyaltyAccount.tier_id_integer'),
            'tier_id.exists' => __('loyalty.loyaltyAccount.tier_id_not_found'),

        ];
    }
}

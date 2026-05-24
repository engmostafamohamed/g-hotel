<?php

namespace App\Http\Requests\V1\Api\Loyalty;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Models\LoyaltyAccount;

class RedeemRewardRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('guest')->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'hotel_id' => 'required|exists:hotel_locations,id',
            'reward_id' => 'required|exists:loyalty_rewards,id',
            'account_id' => 'required|exists:loyalty_accounts,id',
            'points_change' => [
                'required',
                'min:1',
                'integer',
                function ($attribute, $value, $fail) {
                    $account = LoyaltyAccount::find($this->account_id);
                    if ($account) {
                        if ($value > $account->balance) {
                            $fail(__('loyalty/transaction.insufficient_points'));
                        }
                    }
                }
            ],
            'type' => 'required|string|in:redeem,adjust_depit,adjust_credit,expire',
            'status' => 'required|string|in:pending,fulfilled,canceled',
            'source' => 'nullable|string',
            'idempotency_key' => 'nullable|string',
            // 'meta' => 'optional|string',
            'source_id' => 'nullable|integer',
            'valid_from' => 'nullable|date',
            'fulfilled_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
        ];
    }
    protected function prepareForValidation(): void
    {
        $this->merge([
            'hotel_id' => $this->header('hotel_id'),
        ]);
    }

    public function messages(): array
    {
        return [
            'hotel_id.required' => __('loyalty.hotel_id_required'),
            'hotel_id.exists' => __('loyalty.hotel_id_not_found'),

            'reward_id.required' => __('loyalty.reward_id_required'),
            'reward_id.exists' => __('loyalty.reward_id_not_found'),

            'account_id.required' => __('loyalty.account_id_required'),
            'account_id.exists' => __('loyalty.account_id_not_found'),

            'points_change.required' => __('loyalty.points_change_required'),
            'points_change.integer' => __('loyalty.points_change_integer'),
            'points_change.min' => __('loyalty/reward.points_change_min'),

            'type.required' => __('loyalty.type_required'),
            'type.in' => __('loyalty.type_in'),

            'status.required' => __('loyalty.status_required'),
            'status.in' => __('loyalty.status_in'),

            'source.string' => __('loyalty.source_string'),
            'idempotency_key.string' => __('loyalty.idempotency_key_string'),

            'source_id.integer' => __('loyalty.source_id_integer'),

            'valid_from.date' => __('loyalty.valid_from_date'),
            'fulfilled_at.date' => __('loyalty.fulfilled_at_date'),
            'expires_at.date' => __('loyalty.expires_at_date'),
        ];
    }
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'status' => 'error',
            'message' => $validator->errors()->first(),
            'errors' => $validator->errors(),
        ], 422));
    }

}

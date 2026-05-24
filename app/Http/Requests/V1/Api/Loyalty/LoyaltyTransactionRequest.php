<?php

namespace App\Http\Requests\V1\Api\Loyalty;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoyaltyTransactionRequest extends FormRequest
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
            'account_id' => 'required|exists:loyalty_accounts,id',
            'points_change' => 'required|integer',
            'type' => 'required|string|in:earn,redeem,adjust_debit,adjust_credit,expire',
            'source' => 'nullable|string',
            // 'meta' => 'optional|string',
            'source_id' => 'nullable|integer',
            'valid_from' => 'nullable|date',
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
            'account_id.required' => __('loyalty.account_id_required'),
            'account_id.exists' => __('loyalty.account_id_not_found'),
            'points_change.required' => __('loyalty.points_change_required'),
            'points_change.integer' => __('loyalty.points_change_integer'),
            'type.required' => __('loyalty.type_required'),
            'type.in' => __('loyalty.type_in'),
            'source.string' => __('loyalty.source_string'),
            // 'meta.string' => __('loyalty.meta_string'),
            'source_id.integer' => __('loyalty.source_id_integer'),
            'valid_from.date' => __('loyalty.valid_from_date'),
            'expires_at.date' => __('loyalty.expires_at_date'),
        ];
    }


}

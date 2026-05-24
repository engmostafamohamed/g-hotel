<?php

namespace App\Http\Requests\V1\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RequestResetPasswordGuestRequest extends FormRequest
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
            'email' => 'nullable|email:rfc,dns|exists:guests,email|required_without:phone_number',
            'phone_number' => 'nullable|regex:/^[0-9]{12}$/|exists:guests,phone_no|required_without:email'
        ];
    }
    public function messages(): array
    {
        return [
            'email.required_without' => __('validation.email_or_phone_required'),
            'phone_number.required_without' => __('validation.email_or_phone_required'),
            'email.email' => __('validation.email_invalid'),
            'email.exists' => __('validation.email_not_found'),
            'phone_number.regex' => __('validation.phone_invalid'),
            'phone_number.exists' => __('validation.phone_number_not_found'),
        ];
    }
}

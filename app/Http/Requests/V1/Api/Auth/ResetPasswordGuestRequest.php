<?php

namespace App\Http\Requests\V1\Api\Auth;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Validator;
use Carbon\Carbon;

class ResetPasswordGuestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'nullable|email:rfc,dns|exists:guests,email|required_without:phone_number',
            'phone_number' => 'nullable|regex:/^[0-9]{12}$/|exists:guests,phone_no|required_without:email',
            'otp' => 'required|digits:4',
            'newPassword' => 'required|min:6',
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
            'otp.required' => __('validation.otp_required'),
            'otp.digits' => __('validation.otp_digits'),
            'newPassword.required' => __('validation.password_required'),
            'newPassword.min' => __('validation.password_length'),
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $email = $this->input('email');
            $otp = $this->input('otp');

            $latestOtp = DB::table('otp_codes')
                ->where('email', $email)
                ->where('type', 'reset_password')
                ->orderByDesc('created_at')
                ->first();

            if (!$latestOtp) {
                $validator->errors()->add('otp', __('validation.otp_not_found'));
                return;
            }

            if ($latestOtp->code !== $otp) {
                $validator->errors()->add('otp', __('validation.otp_incorrect'));
            }

            if (Carbon::parse($latestOtp->expired_at)->isPast()) {
                $validator->errors()->add('otp', __('validation.otp_expired'));
            }
        });
    }
}

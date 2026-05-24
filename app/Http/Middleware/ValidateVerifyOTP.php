<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;

class ValidateVerifyOTP
{
    public function handle(Request $request, Closure $next)
    {
        $rules = [
            'email' => 'required|email',
            'otp' => 'required|digits:4',
        ];

        $messages = [
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_invalid'),
            'otp.required' => __('validation.otp_required'),
            'otp.digits' => __('validation.otp_digits'),
        ];

        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            $errors = [];
            foreach ($validator->errors()->getMessages() as $field => $messages) {
                foreach ($messages as $msg) {
                    $errors[] = ['field' => $field, 'message' => $msg];
                }
            }

            return ApiResponse::error(__('validation.validation_failed'), $errors, 400);
        }

        return $next($request);
    }
}

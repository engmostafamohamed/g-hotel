<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;

class ValidateResendOTP
{
    public function handle(Request $request, Closure $next)
    {
        $rules = [
            'email' => 'required|email',
        ];

        $messages = [
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_invalid'),
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

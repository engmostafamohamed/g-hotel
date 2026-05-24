<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;

class ValidateGuestLogin
{
    public function handle(Request $request, Closure $next)
    {
        $rules = [
            'email' => 'required|email',
            'password' => 'required',
        ];

        $messages = [
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_invalid'),
            'password.required' => __('validation.password_required'),
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

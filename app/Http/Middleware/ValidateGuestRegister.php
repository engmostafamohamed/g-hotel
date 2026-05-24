<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Helpers\ApiResponse;

class ValidateGuestRegister
{
    public function handle(Request $request, Closure $next)
    {
        $rules = [
            'email' => 'required|email|unique:guests,email',
            'password' => 'required|min:6',
            'firstName' => 'required|string',
            'lastName' => 'required|string'
        ];

        $messages = [
            'email.required' => __('validation.email_required'),
            'email.email' => __('validation.email_invalid'),
            'email.unique' => __('validation.email_taken'),
            'password.required' => __('validation.password_required'),
            'password.min' => __('validation.password_length'),
            'firstName.required' => __('validation.first_name_required'),
            'lastName.required' => __('validation.last_name_required'),
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

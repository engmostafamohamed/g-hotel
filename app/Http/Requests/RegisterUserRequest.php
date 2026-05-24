<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;


class RegisterUserRequest extends FormRequest
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
            // 'phone_no' => ['required', 'unique:guest', 'string', 'size:11', 'regex:/^(010|011|012|015)\d{8}$/'],
            'name' => 'required|string',
            'email' => 'required|email|unique:user,email',
            'password' => 'required|min:8',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        $errMessages = $validator->errors()->all();
        $errMessageString = implode('%', $errMessages);

        throw new HttpResponseException(response()->json([
            'success' => false,
            'message' => $errMessageString
        ], 400));
    }
}

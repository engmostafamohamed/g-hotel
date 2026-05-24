<?php

namespace App\Http\Requests\V1\CRM\Employee;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
class AddEmployeeReqest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // You can add extra authorization logic if needed
        return true;
    }

    /**
     * Get the employee rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'hotel_id'      => 'required|integer|exists:hotel_locations,id',
            'name'          => 'required|string|max:255',
            'phone_number'  => 'required|digits_between:11,15',
            'email'         => [
                'required',
                'email:rfc,dns', // Ensures proper format and valid domain (e.g. gmail.com)
                Rule::unique('employees', 'email'),
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', // Strict regex validation
            ],
            'password'      => 'required|confirmed|min:6',
            'role_ids'      => ['required', 'array'],
            'role_ids.*'    => ['integer', Rule::exists('roles', 'id')],
            // Rule::exists('roles', 'id')->where(function ($query) {
            //     $query->where('name', '!=', 'admin');
            // }),
        ];
    }

    public function messages(): array
    {
        return [
            'hotel_id.required'       => __('employee.hotel_id_required'),
            'hotel_id.integer'        => __('employee.hotel_id_integer'),
            'hotel_id.exists'         => __('employee.hotel_id_not_found'),

            'name.required'           => __('employee.name_required'),
            'email.required'          => __('employee.email_required'),
            'email.email'             => __('employee.email_invalid'),
            'email.regex'             => __('employee.email_invalid'),
            'email.unique'            => __('employee.email_taken'),
            'phone_number.required'   => __('employee.phone_number_required'),
            'phone_number.numeric'    => __('employee.phone_number_numeric'),
            'phone_number.digits_between' => __('employee.phone_number_length'),

            'password.required'       => __('employee.password_required'),
            'password.min'            => __('employee.password_length'),
            'password.confirmed'      => __('employee.password_confirmation'),

            'role_ids.required'         => __('employee.role_required'),
            'role_ids.array'            => __('employee.role_array'),
            'role_ids.*.exists'         => __('employee.role_not_found'),

        ];
    }
}

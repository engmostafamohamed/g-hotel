<?php

namespace App\Http\Requests\V1\CRM\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateEmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // No exception will be thrown
    }

    public function rules(): array
    {
        $employeeId = $this->route('id');
        $hotelId = $this->input('hotel_id');
        return [
            // Check that hotel_id exists
            // 'hotel_id' => 'required|integer|exists:hotel_locations,id',

            // Validate employee exists and belongs to that hotel

            'employee_id' => [
                'required',
                Rule::exists('employees', 'id')->where(function ($query) use ($hotelId) {
                    if (!empty($hotelId)) {
                        $query->where('hotel_id', $hotelId);
                    }
                }),
            ],
            'name' => 'nullable|string|max:255',
            'phone_number' => 'nullable|digits_between:11,15',

            'email' => [
                'nullable',
                'email:rfc,dns', // Ensures proper format and valid domain (e.g. gmail.com)
                Rule::unique('employees', 'email')->ignore($employeeId),
                'regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/', // Strict regex validation
            ],
            'password' => 'nullable|confirmed|min:6',
            'role_ids' => ['nullable','array',],

            'role_ids.*' => [
                'integer',
                Rule::exists('roles', 'id')->where(fn($q) => $q->where('name', '!=', 'admin')),
            ],
        ];
    }

    protected function prepareForValidation(): void
    {
        // Manually merge employee_id from route into request input for validation
        $this->merge([
            'employee_id' => $this->route('id'),
        ]);
    }

    public function messages(): array
    {
        return [
            // 'hotel_id.required' => __('employee.hotel_id_required'),
            // 'hotel_id.integer' => __('employee.hotel_id_integer'),
            // 'hotel_id.exists' => __('employee.hotel_id_not_found'),

            'employee_id.required' => __('employee.employee_id_required'),
            'employee_id.exists' => __('employee.employee_not_found'),

            'name.string' => __('employee.name_string'),
            'name.max' => __('employee.name_max'),

            'phone_number.digits_between' => __('employee.phone_number_length'),

            'email.email' => __('employee.email_invalid'),
            'email.regex' => __('employee.email_invalid'),
            'email.unique' => __('employee.try_another_email'),

            'password.confirmed' => __('employee.password_confirmation'),
            'password.min' => __('employee.password_length'),

            'role_ids.array' => __('employee.role_ids_array'),
            'role_ids.*.integer' => __('employee.role_ids_integer'),
            'role_ids.*.exists' => __('employee.role_not_found'),
        ];
    }
}

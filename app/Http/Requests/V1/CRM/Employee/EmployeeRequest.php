<?php

namespace App\Http\Requests\V1\CRM\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Override to include header values in validation.
     */
    public function validationData()
    {
        return array_merge($this->all(), [
            'hotel_id' => $this->input('hotel_id'),
        ]);
    }

    public function rules(): array
    {
        return [
            'hotel_id' => 'nullable|integer|exists:hotel_locations,id',
        ];
    }

    public function messages(): array
    {
        return [
            // 'hotel_id.required' => __('employee.hotel_id_required'),
            'hotel_id.integer' => __('employee.hotel_id_integer'),
            'hotel_id.exists' => __('employee.hotel_id_not_found'),
        ];
    }
}

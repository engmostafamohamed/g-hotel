<?php

namespace App\Http\Requests\V1\CRM\Service;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreServiceAvailabilityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('employee')->check() && auth('employee')->user()->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'schedules' => 'required|array|min:1',
            'schedules.*.day_of_week' => 'required|in:sunday,monday,tuesday,wednesday,thursday,friday,saturday',
            'schedules.*.work_from' => 'required|date_format:H:i',
            'schedules.*.work_to' => 'required|date_format:H:i|after:schedules.*.work_from',

            'exceptions' => 'nullable|array',
            'exceptions.*.date' => 'required|date',
            'exceptions.*.exception_from' => 'required|date_format:H:i',
            'exceptions.*.exception_to' => 'required|date_format:H:i|after:exceptions.*.exception_from',

            'time_slots' => 'required|array',
            'time_slots.*.start' => 'required|date_format:H:i',
            'time_slots.*.end' => 'required|date_format:H:i|after:time_slots.*.start',
            'time_slots.*.max_capacity' => 'required|integer|min:1',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'success' => false,
            'statusCode' => 422,
            'message' => 'Validation error',
            'errors' => $validator->errors()
        ], 422));
    }
}

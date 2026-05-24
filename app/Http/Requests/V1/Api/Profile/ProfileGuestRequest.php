<?php

namespace App\Http\Requests\V1\Api\Profile;

use Illuminate\Foundation\Http\FormRequest;

class ProfileGuestRequest extends FormRequest
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
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'passport_or_id_num' => 'nullable|string|min:1|max:50',
            'passport_or_id_flag' => 'nullable|in:passport,id',
            'country_id' => 'nullable|exists:countries,id',
            'city_id' => 'nullable|exists:cities,id',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.string' => __('validation.first_name_string'),
            'first_name.max' => __('validation.first_name_max'),
            'last_name.string' => __('validation.last_name_string'),
            'last_name.max' => __('validation.last_name_max'),
            'passport_or_id_num.string' => __('validation.passport_or_id_num_string'),
            'passport_or_id_num.max' => __('validation.passport_or_id_num_max'),
            'passport_or_id_num.min' => __('validation.passport_or_id_num_min'),
            'passport_or_id_flag.in' => __('validation.passport_or_id_flag_in'),
            'country_id.exists' => __('validation.country_id_exists'),
            'city_id.exists' => __('validation.city_id_exists'),
        ];
    }
}

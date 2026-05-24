<?php

namespace App\Http\Requests\V1\Api\Auth;
use Illuminate\Foundation\Http\FormRequest;
use App\Models\City;
class RegisterGuestRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Validation rules.
     */
    public function rules(): array
    {
        return [
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email' => 'required|email:rfc,dns|unique:guests,email',
            'password'   => 'required|min:6',
            'country_code'      => 'nullable|string|exists:countries,country_code',
            'phone_number'      => 'nullable|numeric|min:11',
            'passport_or_id_num'  => 'nullable|numeric',
            'passport_or_id_flag' => 'nullable|in:passport,id',
            'country_id' => 'nullable|exists:countries,id',
            'city_id'    => 'nullable|exists:cities,id',
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'first_name.required' => __('validation.first_name_required'),
            'last_name.required'  => __('validation.last_name_required'),
            'email.required'      => __('validation.email_required'),
            'email.email'         => __('validation.email_invalid'),
            'email.unique'        => __('validation.email_taken'),
            'phone_number.numeric'   => __('validation.phone_number_numeric'),
            'phone_number.min'    => __('validation.phone_number_length'),
            'password.required'   => __('validation.password_required'),
            'password.min'        => __('validation.password_length'),
            'password.confirmed'  => __('validation.password_confirmation'),
            'passport_or_id_flag.in' => __('validation.passport_or_id_flag_invalid'),
            'country_id.exists'   => __('validation.country_invalid'),
            'country_code.exists'   => __('validation.country_code_invalid'),
            'city_id.exists'      => __('validation.city_invalid'),
        ];
    }

    /**
     * Add custom validator for city-country relationship.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $countryId = $this->input('country_id');
            $cityId = $this->input('city_id');

            if ($countryId && $cityId) {
                $city = City::where('id', $cityId)
                            ->where('country_id', $countryId)
                            ->first();

                if (!$city) {
                    $validator->errors()->add('city_id', __('validation.city_not_belong_to_country'));
                }
            }
        });
    }
}

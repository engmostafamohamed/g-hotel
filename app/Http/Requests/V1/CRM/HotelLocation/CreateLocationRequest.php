<?php

namespace App\Http\Requests\V1\CRM\HotelLocation;

use Illuminate\Foundation\Http\FormRequest;

class CreateLocationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        $user = auth('employee')->user();

        return $user && $user->hasRole('admin');
        // return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'location_name' => 'required|string',
            'property_code' => 'required|string|unique:hotel_locations,property_code',
            'display_name' => 'required|string|max:255',
            'default_language' => 'required|string|in:en,ar',
            'default_currency' => 'required|string|in:USD,EGP',
            'lat' => "required|string",
            'long' => "required|string",
            'address' => "required|string",
            'hotel_video_url' => "required|url",
            'timezone' => 'required|string|timezone',
            'is_active' => 'boolean',
        ];
    }
}

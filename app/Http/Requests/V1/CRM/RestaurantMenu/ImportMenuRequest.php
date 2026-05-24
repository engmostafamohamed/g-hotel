<?php

namespace App\Http\Requests\V1\CRM\RestaurantMenu;

use Illuminate\Foundation\Http\FormRequest;

class ImportMenuRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth('employee')->user()?->hasRole('admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'restaurant_id' => 'required|exists:restaurants,id',
            'location' => 'required|string|exists:hotel_locations,property_code',
            'menu_type' => 'required|string',
            // 'csv_file' => 'required|file|mimes:csv,txt',
            'update_existing' => 'boolean',
        ];
    }
}

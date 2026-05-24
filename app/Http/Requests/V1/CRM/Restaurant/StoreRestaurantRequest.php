<?php

namespace App\Http\Requests\V1\CRM\Restaurant;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreRestaurantRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|array',
            'name.en' => 'required|string|unique:restaurants,name->en',
            'name.ar' => 'required|string|unique:restaurants,name->ar',
            'cuisine' => 'required|array',
            'cuisine.en' => 'required|string',
            'cuisine.ar' => 'required|string',
            'hotel_id' => 'required|exists:hotel_locations,id',
            'image' => 'nullable|image|max:25600',
        ];
    }

    public function messages(): array
    {
        return __('restaurant.validation');
    }
}

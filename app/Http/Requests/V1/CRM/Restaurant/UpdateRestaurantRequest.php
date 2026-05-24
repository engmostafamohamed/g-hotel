<?php

namespace App\Http\Requests\V1\CRM\Restaurant;

use App\Http\Requests\ApiFormRequest;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRestaurantRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $restaurantId = $this->route('id'); // since route is POST {id}

        return [
            'name' => 'sometimes|array',
            'name.en' => [
                'sometimes',
                'string',
                Rule::unique('restaurants', 'name->en')->ignore($restaurantId),
            ],
            'name.ar' => [
                'sometimes',
                'string',
                Rule::unique('restaurants', 'name->ar')->ignore($restaurantId),
            ],
            'cuisine' => 'sometimes|array',
            'cuisine.en' => 'sometimes|string',
            'cuisine.ar' => 'sometimes|string',
            'hotel_id' => 'sometimes|exists:hotel_locations,id',
            'image' => 'nullable|image|max:2048',
        ];
    }

    public function messages(): array
    {
        return __('restaurant.validation');
    }
}

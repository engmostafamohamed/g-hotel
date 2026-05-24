<?php

namespace App\Http\Requests\V1\CRM\SeasonalPrice;

use App\Http\Requests\ApiFormRequest;
use App\Rules\NoOverlappingSeasonalPrice;
use Illuminate\Foundation\Http\FormRequest;

class StoreSeasonalPriceRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_type_id' => ['required', 'exists:room_types,id'],
            'from' => ['required', 'date', 'before_or_equal:to'],
            'to' => ['required', 'date', 'after_or_equal:from', new NoOverlappingSeasonalPrice($this->room_type_id)],
            'price' => ['required', 'numeric', 'min:0'],
            'points_discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return __('seasonalPrice.validation');
    }
}

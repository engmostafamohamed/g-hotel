<?php

namespace App\Http\Requests\V1\CRM\SeasonalPrice;

use App\Http\Repository\V1\CRM\SeasonalPrice\SeasonalPriceRepository;
use App\Http\Requests\ApiFormRequest;
use App\Rules\NoOverlappingSeasonalPrice;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSeasonalPriceRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $id = $this->route('id');
        
        // Resolve the repository manually
        $repository = app(SeasonalPriceRepository::class);

        // Safely find the seasonal price
        $seasonalPrice = $repository->find($id);

        return [
            'room_type_id' => ['sometimes', 'exists:room_types,id'],
            'from' => ['sometimes', 'date', 'before_or_equal:to'],
            'to' => [
                'sometimes',
                'date',
                'after_or_equal:from',
                new NoOverlappingSeasonalPrice(
                    $this->room_type_id ?? $seasonalPrice->room_type_id,
                    $id
                )
            ],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'points_discount' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return __('seasonalPrice.validation');
    }
}

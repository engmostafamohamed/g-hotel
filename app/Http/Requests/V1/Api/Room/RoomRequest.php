<?php

namespace App\Http\Requests\V1\Api\Room;

use App\Http\Requests\ApiFormRequest;

class RoomRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // 'hotel_id'      => ['required', 'integer', 'exists:hotel_locations,id'],
            'from_date'        => ['required', 'date', 'after_or_equal:today'],
            'to_date'          => ['required', 'date', 'after_or_equal:from_date'],
            'adults'           => ['required', 'integer', 'min:1'],
            'children'         => ['nullable', 'integer', 'min:0'],

            'room_type_ids'    => ['nullable', 'array'],
            'room_type_ids.*'  => ['integer', 'exists:room_types,id'],

            'room_view_ids'    => ['nullable', 'array'],
            'room_view_ids.*'  => ['integer', 'exists:views,id'],

            'feature_ids'      => ['nullable', 'array'],
            'feature_ids.*'    => ['integer', 'exists:features,id'],

            'min_price'        => ['nullable', 'numeric', 'min:0'],
            'max_price'        => ['nullable', 'numeric', 'gte:min_price'],

            'sort_by'          => ['nullable', 'string', 'in:price_low_to_high,price_high_to_low,rating_high_to_low,rating_low_to_high'],
        ];
    }

    public function messages(): array
    {
        return __('room.validation');
    }
}
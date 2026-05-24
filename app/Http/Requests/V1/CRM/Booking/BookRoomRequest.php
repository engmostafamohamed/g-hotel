<?php

namespace App\Http\Requests\V1\CRM\Booking;

use App\Http\Requests\ApiFormRequest;

class BookRoomRequest extends ApiFormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'hotel_id'     => ['nullable', 'integer', 'exists:hotel_locations,id'],
            'guest_id'     => ['required', 'integer', 'exists:guests,id'],

            'dates' => ['required', 'array'],
            'dates.check_in'  => ['required', 'date', 'after_or_equal:today'],
            'dates.check_out' => ['required', 'date', 'after:dates.check_in'],

            'guests' => ['required', 'array'],
            'guests.adults'   => ['required', 'integer', 'min:1'],
            'guests.children' => ['nullable', 'integer', 'min:0'],

            'room_types' => ['required', 'array', 'min:1'],
            'room_types.*.id'    => ['required', 'integer', 'exists:room_types,id'],
            'room_types.*.count' => ['required', 'integer', 'min:1'],

            // 'upsells'   => ['nullable', 'array'],
            // 'upsells.*' => ['string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return __('booking.validation') ?: [];
    }
}

<?php

namespace App\Http\Requests\V1\Api\Room;

use Illuminate\Foundation\Http\FormRequest;

class BookRoomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // 'room_id'      => ['required', 'integer', 'exists:rooms,id'],
            'hotel_id'     => ['nullable', 'integer', 'exists:hotel_locations,id'],
            'roomType_id' => ['required', 'integer', 'exists:room_types,id'],
            'guest_id'     => ['required', 'integer', 'exists:guests,id'],

            'dates' => ['required', 'array'],
            'dates.check_in'  => ['required', 'date', 'after_or_equal:today'],
            'dates.check_out' => ['required', 'date', 'after:dates.check_in'],

            'departure_date' => ['nullable', 'date', 'after_or_equal:dates.check_out'],
            'departure_time' => ['nullable', 'date_format:H:i'],

            'guests' => ['required', 'array'],
            'guests.adults'   => ['required', 'integer', 'min:1'],
            'guests.children' => ['nullable', 'integer', 'min:0'],

            'quantity' => ['required', 'integer', 'min:1'],

            'upsells'   => ['nullable', 'array'],
            'upsells.*' => ['string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            // Room, Hotel, RoomType, Guest
            // 'room_id.required' => __('bookRoom.room_id_required'),
            // 'room_id.integer'  => __('bookRoom.room_id_integer'),
            // 'room_id.exists'   => __('bookRoom.room_id_exists'),

            'hotel_id.required' => __('bookRoom.hotel_id_required'),
            'hotel_id.integer'  => __('bookRoom.hotel_id_integer'),
            'hotel_id.exists'   => __('bookRoom.hotel_id_exists'),

            'roomType_id.required' => __('bookRoom.room_type_id_required'),
            'roomType_id.integer'  => __('bookRoom.room_type_id_integer'),
            'roomType_id.exists'   => __('bookRoom.room_type_id_exists'),

            'guest_id.required' => __('bookRoom.guest_id_required'),
            'guest_id.integer'  => __('bookRoom.guest_id_integer'),
            'guest_id.exists'   => __('bookRoom.guest_id_exists'),

            // Dates
            'dates.required'      => __('bookRoom.dates_required'),
            'dates.array'         => __('bookRoom.dates_array'),
            'dates.check_in.required' => __('bookRoom.dates_check_in_required'),
            'dates.check_in.date'     => __('bookRoom.dates_check_in_date'),
            'dates.check_in.after_or_equal' => __('bookRoom.dates_check_in_after_or_equal'),
            'dates.check_out.required' => __('bookRoom.dates_check_out_required'),
            'dates.check_out.date'     => __('bookRoom.dates_check_out_date'),
            'dates.check_out.after'    => __('bookRoom.dates_check_out_after'),

            'departure_date.date'          => __('bookRoom.departure_date_date'),
            'departure_date.after_or_equal'=> __('bookRoom.departure_date_after_or_equal'),
            'departure_time.date_format'   => __('bookRoom.departure_time_format'),

            // Guests
            'guests.required' => __('bookRoom.guests_required'),
            'guests.array'    => __('bookRoom.guests_array'),
            'guests.adults.required' => __('bookRoom.guests_adults_required'),
            'guests.adults.integer'  => __('bookRoom.guests_adults_integer'),
            'guests.adults.min'      => __('bookRoom.guests_adults_min'),
            'guests.children.integer'=> __('bookRoom.guests_children_integer'),
            'guests.children.min'    => __('bookRoom.guests_children_min'),

            // Quantity
            'quantity.required' => __('bookRoom.quantity_required'),
            'quantity.integer'  => __('bookRoom.quantity_integer'),
            'quantity.min'      => __('bookRoom.quantity_min'),

            // Upsells
            'upsells.array'     => __('bookRoom.upsells_array'),
            'upsells.*.string'  => __('bookRoom.upsells_string'),
            'upsells.*.max'     => __('bookRoom.upsells_max'),
        ];
    }

}

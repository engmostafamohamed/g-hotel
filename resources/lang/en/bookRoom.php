<?php

return [
    'room_id_required' => 'Room is required.',
    'room_id_integer'  => 'Room must be a number.',
    'room_id_exists'   => 'Selected room does not exist.',

    'hotel_id_required' => 'Hotel is required.',
    'hotel_id_integer'  => 'Hotel must be a number.',
    'hotel_id_exists'   => 'Selected hotel does not exist.',

    'room_type_id_required' => 'Room type is required.',
    'room_type_id_integer'  => 'Room type must be a number.',
    'room_type_id_exists'   => 'Selected room type does not exist.',

    'guest_id_required' => 'Guest is required.',
    'guest_id_integer'  => 'Guest must be a number.',
    'guest_id_exists'   => 'Selected guest does not exist.',

    'dates_required' => 'Dates are required.',
    'dates_array'    => 'Dates must be in array format.',
    'dates_check_in_required' => 'Check-in date is required.',
    'dates_check_in_date'     => 'Check-in must be a valid date.',
    'dates_check_in_after_or_equal' => 'Check-in must be today or later.',
    'dates_check_out_required' => 'Check-out date is required.',
    'dates_check_out_date'     => 'Check-out must be a valid date.',
    'dates_check_out_after'    => 'Check-out must be after check-in.',

    'departure_date_date' => 'Departure date must be a valid date.',
    'departure_date_after_or_equal' => 'Departure date must be after check-out.',
    'departure_time_format' => 'Departure time must be in H:i format.',

    'guests_required' => 'Guests information is required.',
    'guests_array'    => 'Guests must be in array format.',
    'guests_adults_required' => 'Number of adults is required.',
    'guests_adults_integer'  => 'Adults must be a number.',
    'guests_adults_min'      => 'At least one adult is required.',
    'guests_children_integer'=> 'Children must be a number.',
    'guests_children_min'    => 'Children cannot be negative.',

    'quantity_required' => 'Quantity is required.',
    'quantity_integer'  => 'Quantity must be a number.',
    'quantity_min'      => 'Quantity must be at least 1.',

    'upsells_array'    => 'Upsells must be an array.',
    'upsells_string'   => 'Each upsell must be a string.',
    'upsells_max'      => 'Upsell may not be greater than 255 characters.',
    'no_available_rooms' => 'No available rooms for this room type.'
];


<?php

return [
    'total_nights_fetched' => 'Total nights fetched successfully.',
    'guest_not_found' => 'The specified guest could not be found.',
    'unexpected_fetch' => 'An unexpected error occurred while fetching the total nights.',

    'hotel_id_missing' => 'Hotel context is missing. Please specify a valid hotel.',
    'hotel_id_mismatch' => 'Hotel context mismatch detected.',
    'room_type_hotel_mismatch' => 'The selected room type does not belong to this hotel.',
    'employee_not_authenticated' => 'Employee must be authenticated to perform this action.',

    'no_available_rooms' => 'No available rooms for the selected dates.',
    'booking_unsuccessful' => 'Booking could not be completed because not all requested room types are available.',
    'booking_created_successfully' => 'Booking created successfully.',
    'error_happened' => 'An error occurred while creating the booking.',

    'summary' => [
        'booked' => 'Booked Rooms',
        'unavailable' => 'Unavailable Rooms',
        'total_price' => 'Total Price',
        'total_nights' => 'Total Nights',
    ],

    'validation' => [
        'room_types.required' => 'The required list of room types is missing.',
        'room_types.array'    => 'The list of room types must be an array.',
        'room_types.*.id.required' => 'Room type ID is required for each room type.',
        'room_types.*.id.exists'   => 'The selected room type does not exist.',
        'room_types.*.count.required' => 'The number of rooms is required for each room type.',
        'room_types.*.count.min'      => 'There must be at least one room for each room type.',
    ],
];

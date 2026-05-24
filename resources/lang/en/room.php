<?php

return [
    'data_not_found' => 'Data not found.',
    'room_not_found' => 'Rooms not found.',
    'data_fetched_successfully' => 'Data fetched successfully.',
    'data_added_successfully' => 'Data added successfully.',
    'image_not_found' => 'Image is required.',
    'error_happend' => 'Something went wrong.',

    'hotel_id_required' => 'Hotel ID is required.',
    'hotel_id_integer' => 'Hotel ID must be an integer.',
    'hotel_id_not_found' => 'The selected hotel does not exist.',

    'room_id_required' => 'Room ID is required.',
    'room_id_not_found' => 'The selected room does not exist.',
    'room_id_integer' => 'Room ID must be an integer.',

    'number_of_adults_required' => 'Number of adults is required.',
    'number_of_adults_integer' => 'Number of adults must be an integer.',
    'number_of_adults_min' => 'Number of adults must be at least 0.',

    'number_of_children_required' => 'Number of children is required.',
    'number_of_children_integer' => 'Number of children must be an integer.',
    'number_of_children_min' => 'Number of children must be at least 0.',

    'available_quantity_required' => 'Available quantity is required.',
    'available_quantity_integer' => 'Available quantity must be an integer.',
    'available_quantity_min' => 'Available quantity must be at least 0.',

    'images_required' => 'Images are required.',
    'images_array' => 'Images must be an array.',
    'image_file' => 'Each image must be a valid file.',
    'image_mimes' => 'Images must be of type: jpg, jpeg, png.',
    'image_max' => 'Images must not exceed 2MB in size.',

    'room_name_en_required' => 'Room name in English is required.',
    'room_name_ar_required' => 'Room name in Arabic is required.',
    'room_description_en_required' => 'Room description in English is required.',
    'room_description_ar_required' => 'Room description in Arabic is required.',

    'from_date.required' => 'From date is required.',
    'from_date.date' => 'From date must be a valid date.',
    'from_date.after_or_equal' => 'From date must be today or later.',

    'to_date.required' => 'To date is required.',
    'to_date.date' => 'To date must be a valid date.',
    'to_date.after_or_equal' => 'To date must be after or equal to from date.',

    'adults.required' => 'Number of adults is required.',
    'adults.integer' => 'Number of adults must be an integer.',
    'adults.min' => 'Number of adults must be at least 1.',

    'children.required' => 'Number of children is required.',
    'children.integer' => 'Number of children must be an integer.',
    'children.min' => 'Number of children must be at least 0.',

    'room_view_ids.array' => 'Room view must be an array.',
    'room_view_ids.integer' => 'Each room view must be an integer.',
    'room_view_ids.exists' => 'Selected room view is invalid.',

    'room_type_id.integer' => 'Room type ID must be an integer.',
    'room_type_id.exists' => 'Room type does not exist.',

    'room_number.string' => 'Room number must be a string.',

    'min_price.integer' => 'Minimum price must be an integer.',
    'min_price.min' => 'Minimum price must be at least 0.',

    'max_price.integer' => 'Maximum price must be an integer.',
    'max_price.gte' => 'Maximum price must be greater than or equal to minimum price.',

    'feature_ids.array' => 'Features must be an array.',
    'feature_ids.integer' => 'Each feature must be an integer.',
    'feature_ids.exists' => 'Selected feature is invalid.',


    'fetched'            => 'Rooms fetched successfully.',
    'fetched_single'     => 'Room fetched successfully.',
    'created'            => 'Room created successfully.',
    'bulk_created'       => 'Rooms created successfully.',
    'updated'            => 'Room updated successfully.',
    'deleted'            => 'Room deleted successfully.',
    'not_found'          => 'Room not found.',
    'unexpected_fetch'   => 'An unexpected error occurred while fetching rooms.',
    'unexpected_create'  => 'An unexpected error occurred while creating the room(s).',
    'unexpected_update'  => 'An unexpected error occurred while updating the room.',
    'unexpected_delete'  => 'An unexpected error occurred while deleting the room.',

    'filter_fetched'          => 'Room filters fetched successfully.',
    'unexpected_filter_fetch' => 'An unexpected error occurred while fetching room filters.',
    'hotel_context_required' => 'Hotel context is required to fetch room filters.',
    'filter_no_data'          => 'No filter data available for this hotel.',

    'available_rooms_fetched' => 'Available rooms fetched successfully.',
    'no_available_rooms' => 'No available rooms for the selected dates.',
    'unexpected_fetch' => 'An unexpected error occurred while fetching available rooms.',
    'hotel_context_required' => 'Hotel context is required to fetch available rooms.',

    'validation' => [
        'room_number.required' => 'The room number is required.',
        'room_number.string'   => 'The room number must be a string.',
        'room_number.max'      => 'The room number may not be greater than :max characters.',
        'room_number.unique'   => 'The room number has already been taken for this hotel.',
        'room_type_id.required'=> 'The room type is required.',
        'room_type_id.exists'  => 'The selected room type does not exist.',
        'room_numbers.required'=> 'You must provide at least one room number.',
        'room_numbers.array'   => 'The room numbers must be an array.',
        'room_numbers.*.distinct' => 'Room numbers must be distinct.',
        'room_numbers.*.unique'   => 'One or more room numbers already exist for this hotel.',

        'hotel_id.required' => 'The hotel ID is required.',
        'from_date.required' => 'The check-in date is required.',
        'to_date.required' => 'The check-out date is required.',
        'from_date.after_or_equal' => 'The check-in date must be today or later.',
        'to_date.after_or_equal' => 'The check-out date must be after the check-in date.',
        'adults.required' => 'Please specify the number of adults.',
        'adults.min' => 'There must be at least one adult.',
        'children.min' => 'The number of children cannot be negative.',
        'room_type_ids.array' => 'The selected room types must be an array.',
        'room_type_ids.*.integer' => 'Each room type ID must be an integer.',
        'room_type_ids.*.exists' => 'One or more selected room types are invalid.',
        'room_view_ids.array' => 'The selected views must be an array.',
        'room_view_ids.*.integer' => 'Each view ID must be an integer.',
        'room_view_ids.*.exists' => 'One or more selected views are invalid.',
        'feature_ids.array' => 'The selected features must be an array.',
        'feature_ids.*.integer' => 'Each feature ID must be an integer.',
        'feature_ids.*.exists' => 'One or more selected features are invalid.',
        'min_price.numeric' => 'The minimum price must be a number.',
        'min_price.min' => 'The minimum price must be at least 0.',
        'max_price.numeric' => 'The maximum price must be a number.',
        'max_price.gte' => 'The maximum price must be greater than or equal to the minimum price.',
        'sort_by.in' => 'Invalid sort option selected.',
        'hotel_context_required' => 'Hotel context not set.',
    ],
];

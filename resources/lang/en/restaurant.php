<?php
return [
    'schedules_array' => 'The schedule must be an array.',
    'schedule_day_required' => 'The day field is required for each schedule.',
    'schedule_day_string' => 'The day must be a valid string.',
    'schedule_day_in' => 'The day must be one of the weekdays from Saturday to Friday.',

    'schedule_opening_required' => 'Opening time is required when entering a schedule.',
    'schedule_opening_format' => 'The opening time must be in the format HH:MM (e.g., 09:00).',

    'schedule_closing_required' => 'Closing time is required when entering a schedule.',
    'schedule_closing_format' => 'The closing time must be in the format HH:MM (e.g., 17:00).',
    'schedule_closing_after_opening' => 'The closing time must be after the opening time.',

    'hotel_id_required' => 'Hotel ID is required.',
    'hotel_id_integer' => 'Hotel ID must be an integer.',
    'hotel_id_not_found' => 'The selected hotel was not found.',


    'restaurant_id_required' => 'Restaurant ID is required.',
    'restaurant_id_integer' => 'Restaurant ID must be an integer.',
    'restaurant_id_not_found' => 'The selected restaurant was not found.',

    'no_changes' => 'Not found any changes.',
    'in_dining_must_bool' => 'In_dining must be true or false.',
    'room_service_must_bool' => 'Room_service must be true or false.',
    'data_added_successfully' => 'Data added successfully .',

    'menu_data_not_found' => 'Restaurant data not found.',
    'menu_fetched_successfully' => 'Menu fetched successfully.',

    'schedule_day_invalid' => 'Invalid day of week in schedule.',
    'schedule_work_from_required' => 'Work from time is required.',
    'schedule_work_from_format' => 'Work from time must follow the H:i format.',
    'schedule_work_to_required' => 'Work to time is required.',
    'schedule_work_to_format' => 'Work to time must follow the H:i format.',
    'schedule_work_to_after' => 'Work to time must be after work from time.',

    'exception_date_required' => 'Exception date is required.',
    'exception_date_format' => 'Exception date must follow the Y-m-d format.',
    'exception_from_required' => 'Exception start time is required.',
    'exception_from_format' => 'Exception start time must follow the H:i format.',
    'exception_to_required' => 'Exception end time is required.',
    'exception_to_format' => 'Exception end time must follow the H:i format.',

    'exception_from_must_be_before_to' => 'The exception start time must be before the end time.',
    'work_from_must_be_before_to' => 'The work start time must be before the end time.',

    'fetched'            => 'Restaurants fetched successfully.',
    'fetched_single'     => 'Restaurant fetched successfully.',
    'created'            => 'Restaurant created successfully.',
    'updated'            => 'Restaurant updated successfully.',
    'deleted'            => 'Restaurant deleted successfully.',
    'not_found'          => 'Restaurant not found.',
    'unexpected'         => 'An unexpected error occurred.',
    'unexpected_create'  => 'An unexpected error occurred while creating the restaurant.',
    'unexpected_update'  => 'An unexpected error occurred while updating the restaurant.',
    'unexpected_delete'  => 'An unexpected error occurred while deleting the restaurant.',

'validation' => [
    'name.en.required' => 'The English restaurant name is required.',
    'name.en.unique'   => 'The English restaurant name has already been taken.',
    'name.ar.required' => 'The Arabic restaurant name is required.',
    'name.ar.unique'   => 'The Arabic restaurant name has already been taken.',
    'cuisine.required'    => 'The cuisine is required.',
    'cuisine.array'       => 'The cuisine must be an array.',
    'cuisine.en.required' => 'The English cuisine is required.',
    'cuisine.ar.required' => 'The Arabic cuisine is required.',
    'hotel_id.required'   => 'The hotel is required.',
    'hotel_id.exists'     => 'The selected hotel does not exist.',
    'image.image'         => 'The image must be an image file.',
    'image.max'           => 'The image may not be greater than :max kilobytes.',
],
];

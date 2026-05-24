<?php

return [
    'created' => 'Feedback submitted successfully.',
    'updated' => 'Feedback updated successfully.',
    'deleted' => 'Feedback deleted successfully.',
    'fetched' => 'Feedbacks retrieved successfully.',
    'fetched_single' => 'Feedback retrieved successfully.',
    'unauthorized_hotel_filter' => 'The provided hotel_id does not match your authorized hotel.',
    'not_found' => 'The requested feedback could not be found.',
    'unexpected_create' => 'An unexpected error occurred while creating the feedback.',
    'unexpected_update' => 'An unexpected error occurred while updating the feedback.',
    'unexpected_delete' => 'An unexpected error occurred while deleting the feedback.',
    'unexpected_fetch' => 'An unexpected error occurred while fetching the feedback.',


    'validation' => [
        'booking_id.required_without' => 'A booking ID is required if no service reservation ID is provided.',
        'booking_id.exists' => 'The selected booking does not exist.',
        'booking_id.unique' => 'This booking already has feedback.',
        'service_reservation_id.required_without' => 'A service reservation ID is required if no booking ID is provided.',
        'service_reservation_id.exists' => 'The selected service reservation does not exist.',
        'service_reservation_id.unique' => 'This service reservation already has feedback.',
        'rating.required' => 'A rating is required.',
        'rating.integer' => 'The rating must be an integer.',
        'rating.min' => 'The rating must be at least :min.',
        'rating.max' => 'The rating may not be greater than :max.',
        'comment.string' => 'The comment must be a string.',
        'comment.max' => 'The comment may not be greater than :max characters.',
        'booking_not_owned' => 'You cannot leave feedback for a booking that is not yours.',
        'booking_wrong_hotel' => 'This booking does not belong to the selected hotel.',
        'service_not_owned' => 'You cannot leave feedback for a service reservation that is not yours.',
        'service_wrong_hotel' => 'This service reservation does not belong to the selected hotel.',
    ],
];

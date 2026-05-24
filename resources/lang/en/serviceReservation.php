<?php

return [

    // ================= General & Business Logic Messages =================
    'fetched'                   => 'Reservations fetched successfully.',
    'fetched_single'            => 'Reservation fetched successfully.',
    'created'                   => 'Reservation created successfully.',
    'updated'                   => 'Reservation updated successfully.',
    'deleted'                   => 'Reservation deleted successfully.',
    'not_found'                 => 'Reservation not found.',
    'unauthorized'              => 'Unauthorized.',
    'validation_failed'         => 'Validation exception.',
    'unexpected_fetch'          => 'An unexpected error occurred while fetching reservations.',
    'unexpected_create'         => 'Failed to create reservation.',
    'unexpected_update'         => 'Failed to update reservation.',
    'unexpected_delete'         => 'An error occurred while deleting reservation.',
    'guests_fetched'            => 'Guests with reservations fetched successfully.',
    'service_category_not_found'=> 'The specified service category could not be found.',
    'unauthorized_hotel_filter' => 'You are not authorized to filter by this hotel.',

    // ================= Business Rule / Service Layer Messages =================
    'datetime_required'         => 'Date, from, and to times are required for schedulable services.',
    'schedule_unavailable'      => 'This service is not available on the selected day.',
    'exception_unavailable'     => 'Service is not available on the selected time due to an exception.',
    'invalid_time_slot'         => 'Invalid time slot for this service.',
    'fully_booked'              => 'This time slot is fully booked.',
    'cancellation_mismatch'     => 'Cancellation reason can only be set if the status is "cancelled".',
    'not_allowed_modify'        => 'You are not allowed to modify this reservation.',

    // ================= Validation Messages =================
    'validation' => [
        'service_id.required'        => 'The service is required.',
        'service_id.exists'          => 'The selected service does not exist.',
        'guest_id.required'          => 'The guest is required.',
        'guest_id.exists'            => 'The selected guest does not exist.',
        'date.required'              => 'The date is required for this service.',
        'date.date'                  => 'The date must be a valid date.',
        'date.after_or_equal'        => 'The date must be today or later.',
        'from.required'              => 'The from time is required for this service.',
        'from.date_format'           => 'The from time must be in H:i format.',
        'to.required'                => 'The to time is required for this service.',
        'to.date_format'             => 'The to time must be in H:i format.',
        'to.after'                   => 'The to time must be after the from time.',
        'to.after_now'               => 'The "to" time must be after the current time when the date is today.',
        'notes.string'               => 'Notes must be a string.',
        'status.in'                  => 'The status must be one of: pending, confirmed, cancelled, or completed.',
        'cancellation_reason.string' => 'The cancellation reason must be a string.',
    ],
];
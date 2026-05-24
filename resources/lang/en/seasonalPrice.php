<?php

return [
    'fetched'           => 'Seasonal prices fetched successfully.',
    'fetched_single'    => 'Seasonal price fetched successfully.',
    'created'           => 'Seasonal price created successfully.',
    'updated'           => 'Seasonal price updated successfully.',
    'deleted'           => 'Seasonal price deleted successfully.',
    'not_found'         => 'Seasonal price not found.',
    'unexpected_fetch'  => 'An error occurred while fetching seasonal prices.',
    'unexpected_create' => 'Failed to create seasonal price.',
    'unexpected_update' => 'Failed to update seasonal price.',
    'unexpected_delete' => 'An error occurred while deleting seasonal price.',

    'validation' => [
        'room_type_id.required' => 'The room type is required.',
        'room_type_id.exists'   => 'The selected room type does not exist.',
        'from.required'         => 'The start date is required.',
        'from.date'             => 'The start date must be a valid date.',
        'from.before_or_equal'  => 'The start date must be before or equal to the end date.',
        'to.required'           => 'The end date is required.',
        'to.date'               => 'The end date must be a valid date.',
        'to.after_or_equal'     => 'The end date must be after or equal to the start date.',
        'price.required'        => 'The price is required.',
        'price.numeric'         => 'The price must be a number.',
        'price.min'             => 'The price must be at least 0.',
        'points_discount.numeric' => 'The points discount must be a number.',
        'points_discount.min'     => 'The points discount must be at least 0.',
        'points_discount.max'     => 'The points discount may not be greater than 100.',
        'overlap'                => 'There is already an overlapping seasonal price for this room type.',
    ],
];
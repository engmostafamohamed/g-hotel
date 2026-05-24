<?php

// return [
//     'name_required' => 'The feature name is required.',
//     'name_en_required' => 'The English name is required.',
//     'name_ar_required' => 'The Arabic name is required.',
//     'name_en_string' => 'The English name must be a string.',
//     'name_ar_string' => 'The Arabic name must be a string.',
//     'hotel_id_required' => 'Hotel ID is required.',
//     'hotel_id_not_found' => 'Hotel not found.',
//     'logo_must_be_image' => 'The logo must be an image.',
//     'logo_too_large' => 'The logo may not be greater than 5MB.',
//     'db_error' => 'Database error occurred.',
//     'unexpected_error' => 'Unexpected error occurred.',
// ];

return [
    'fetched'            => 'Features fetched successfully.',
    'fetched_single'     => 'Feature fetched successfully.',
    'created'            => 'Feature created successfully.',
    'updated'            => 'Feature updated successfully.',
    'deleted'            => 'Feature deleted successfully.',
    'not_found'          => 'Feature not found.',
    'unexpected'         => 'An unexpected error occurred.',
    'unexpected_create'  => 'An unexpected error occurred while creating the feature.',
    'unexpected_update'  => 'An unexpected error occurred while updating the feature.',
    'unexpected_delete'  => 'An unexpected error occurred while deleting the feature.',
    'validation' => [
        'name.en.required' => 'The English feature name is required.',
        'name.en.unique'   => 'The English feature name has already been taken.',
        'name.ar.required' => 'The Arabic feature name is required.',
        'name.ar.unique'   => 'The Arabic feature name has already been taken.',
        'hotel_id.required'=> 'The hotel is required.',
        'hotel_id.exists'  => 'The selected hotel does not exist.',
        'logo.image'       => 'The logo must be an image file.',
        'logo.max'         => 'The logo may not be greater than :max kilobytes.',
    ],
];